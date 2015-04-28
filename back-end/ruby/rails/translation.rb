class Translation < ActiveRecord::Base
  include ActsAsCustomizablePublisherEntity
  include ActsAsSecondLevelCachable

  has_many :site_custom_translations, -> { where site_id: Site.current.blank? ? nil : Site.current.id }, dependent: :destroy
  accepts_nested_attributes_for :site_custom_translations, update_only: true

  after_commit :clear_cache

  validates_presence_of :key
  validates_presence_of :locale

  def clear_cache
    Rails.cache.delete([Site.current ? Site.current.id : 'default', locale.to_s, key.to_s])
  end

  def self.store locale, opts={}
    opts.each do |k,v|
      self.create(locale: locale, key: k, value: v)
    end
  end

  module Serialize
    # Import Yaml translation file to database
    #
    # @param  yaml [String] Filename of translation yaml file
    def import_yaml(yaml)
      hash = YAML.load(yaml)
      hash.each do |locale, data|
        hash_flatten(data).each do |key, value|
          c = where(key: key, locale: locale).first
          c ||= new(key: key, locale: locale)
          c.value = value
          c.save
        end
      end
    end

    # Export translation table to yaml
    #
    # @return [Yaml]
    def export_yaml
      hash = {}

      all.each do |c|
        next unless c.value
        hash_fatten!(hash, [c.locale].concat(c.key.split(".")), c.value)
      end

      hash.to_yaml
    end

    # Flattens given hash
    # @param  hash [Hash]
    #
    # @return [Hash] hash_flatten
    # @example e.g \\{"foo"=>{"a"=>"1", "b"=>"2"}} ----> {"foo.a"=>1, "foo.b"=>2 }
    def hash_flatten(hash)
      result = {}

      hash.each do |key, value|
        if value.is_a? Hash
          hash_flatten(value).each { |k,v| result["#{key}.#{k}"] = v }
        else
          result[key] = value
        end
      end

      result
    end

    # Fatten given hash
    #
    # @param  hash [Hash]
    # @param  keys [Strings]
    # @param  value [String, Number]
    #
    # @return [Hash] hash_fatten
    # @example e.g (\\{"a"=>{"b"=>{"e"=>"f"}}}, ["a","b","c"], "d") ----> {"a"=>{"b"=>{"c"=>"d", "e"=>"f" } } }
    def hash_fatten!(hash, keys, value)
      if keys.length === 1
        hash[keys.first] = value
      else
        head = keys.first
        rest = keys[1..-1]
        hash[head] ||= {}
        hash_fatten!(hash[head], rest, value)
      end
    end

  end

  extend Serialize

  def self.cached_all
    Rails.cache.fetch([name, 'all'], expires_in: 3.hours) do
      self.all
    end
  end

  def self.cached_find_all_with_distinct_locale
    Rails.cache.fetch([name, 'find_all_with_distinct_locale'], expires_in: 3.hours) do
      self.find(:all, select: 'distinct locale').map(&:locale)
    end
  end

  def self.cached_where_locale_and_key_first(locale, key)
    Rails.cache.fetch([name, "where_locale_and_key_first_#{locale}_#{key}"], expires_in: 3.hours) do
      Translation.where(locale: locale.to_s, key: key.to_s).first
    end
  end

  def cached_send_site_custom
    Rails.cache.fetch([self.class.name, 'send_site_custom'], expires_in: 12.hours) do
      self.send('site_custom_' + self.class.name.downcase)
    end
  end
end
