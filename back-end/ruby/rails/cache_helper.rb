module Admin::CacheHelper
  include OptionHelper

  def purge_table_key(object)
    object.purge_table_key
  end

  def purge_resource_key(object)
    object.purge_resource_key
  end

  def purge(url)
    @settings = Site.current.setting
    proxy_server_type = get_option('caching.proxy_server_type')
    case proxy_server_type
    when 'varnish'
      varnish_purge(url)
    when 'fastly'
      if target_site = Site.find_by(hostname: url.sub(/^https?\:\/\/(www.)?/,'').sub(/\/+$/,''))
        @settings.purge_key "home_#{target_site.id}"
      else
        auth_key = get_option('caching.fastly_auth_key')
        fastly_purge(url, auth_key)
      end
    end
  end

  private
    def varnish_purge(url)
      uri = URI(url)
      Net::HTTP.start(uri.host,uri.port) do |http|
        http.request Net::HTTP::Purge.new uri.request_uri
        http.request Net::HTTP::Ban.new uri.request_uri
      end
    end

    def fastly_purge(url, auth_key)
      uri = URI(url)
      Net::HTTP.start(uri.host,uri.port) do |http|
        req = Net::HTTP::Purge.new uri.request_uri
        req['Fastly-Key'] = auth_key
        http.request req
      end
    end
end
