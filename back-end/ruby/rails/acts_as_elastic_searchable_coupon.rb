module ActsAsElasticSearchableCoupon
  extend ActiveSupport::Concern

  included do
    searchkick word_start: [:shop_title, :category_names, :tag_words]

    scope :search_import, -> { includes(:shop) }

    def self.elastic_active params, options = {}
      begin
        self.search params[:query],
          page: (options[:paginate] === false) ? nil : (params[:page].present? ? params[:page] : 1),
          per_page: (options[:paginate] === false) ? nil : (default_per_page rescue 5),
          where: build_where(params),
          fields:  [
            {"shop_title^20" => :word_start},
            {"title^5" => :word_start},
            {"category_names^3" => :word_start},
            {"tag_words^3" => :word_start},
            "description",
            "shop_description",
            "_all"
          ],
          order: { _score: :desc },
          partial: true,
          autocomplete: options[:autocomplete] || false
      rescue Exception => e
        raise ActiveRecord::RecordNotFound
      end
    end

    def self.build_where params
      where = {
        site_id: Site.current.id,
        end_date: {gt: Time.zone.now},
      }
      or_query   = []
      or_query   << { shop_id: params[:shop_id] } if params[:shop_id].present?
      or_query   << { category_ids: params[:category_id] } if params[:category_id].present?
      where[:or] = [or_query] if or_query.present?
      where
    end

    def should_index?
      status == 'active' && shop.present? && shop.status == 'active' && (end_date == nil || end_date >= Time.zone.now) && (start_date == nil || start_date <= Time.zone.now)
    end

    def search_data
      {
        title: title,
        shop_title: shop_title,
        site_id: site_id,
        description: description,
        shop_description: shop_description,
        end_date: end_date,
        tag_words: tag_words,
        category_names: category_names,
        shop_id: shop_id,
        category_ids: category_ids
      }
    end

    def shop_title
      shop.title if shop.present?
    end

    def shop_description
      shop.description if shop.present?
    end

    def category_ids
      categories.pluck(:id) if categories.present?
    end

    def category_names
      categories.pluck(:name) if categories.present?
    end

    def shop_status
      shop.status if shop.present?
    end

    def tag_words
      tags.pluck(:word) if tags.present?
    end
  end
end
