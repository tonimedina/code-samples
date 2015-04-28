class Transaction < ActiveRecord::Base
  include ActsAsSecondLevelCachable
  include ActsAsSiteable

  CLICKOUT_VALUE = 0.25

  belongs_to :tracking_click
  belongs_to :affiliate_network

  # Scopes
  scope :by_date_range, ->(range){ where tracking_date: range }

  # Scope to fetch Transactions for either
  # a) the current site of the publisher, or
  # b) all sites of any other user then publisher
  #
  # @return [hash]
  def self.by_current_user_and_site
    if Site.current
      where site_id: Site.current.id
    else
      where site_id: User.current.sites.pluck(:id)
    end
  end

  def self.get_transaction_stats_by_date_range(range, currency)
    self
      .by_current_user_and_site
      .by_date_range(range)
      .where(:currency => currency)
      .group(:state)
      .count
  end

  def self.get_currencies
    self
      .by_current_user_and_site
      .select(:currency)
      .group(:currency)
      .map(&:currency)
  end

  def self.get_commissions_stats_by_date_range(range, currency)
    commissions_stats = {}

    %w(open approved rejected confirmed).each do |state|
      commissions_stats[state] = self
        .by_current_user_and_site
        .by_date_range(range)
        .where(state: state)
        .where(:currency => currency)
        .sum(:commission)
        .round(2)
    end

    commissions_stats
  end

  def self.stats
    defaults = {'open' => 0, 'approved' => 0, 'rejected' => 0, 'confirmed' => 0}
    today = Time.zone.now
    start_date = today.beginning_of_month.to_s(:db)
    end_date = today.end_of_month.to_s(:db)
    allowed_sites = (Site.current) ? Site.current.id : User.current.get_current_user_sites

    stats = self.where(tracking_date: start_date..end_date, site_id: allowed_sites).group(:state).count
    defaults.merge(stats)
  end

  def self.sales_stats(return_single_currency=false)
    sales_stats = {}
    currency_amounts = {}
    transactions_currencies = Transaction.get_currencies

    transactions_currencies.each do |currency|
      sales_stats[currency] = Transaction.sales_stats_by_currency(currency)
      currency_amounts[currency] = sales_stats[currency]['amount']
    end

    return {'amount' => '0.0', 'commission' => '0.0'} if sales_stats.blank?

    if return_single_currency
      currency = best_currency(currency_amounts)
      
      return sales_stats[currency], currency
    end

    sales_stats
  end

  private
    def self.sales_stats_by_currency(currency)
      sales_hash = {'amount' => 0, 'commission' => 0, 'comission_share_percentage' => 100}
      today = Time.zone.now
      start_date = today.beginning_of_month.to_s(:db)
      end_date = today.end_of_month.to_s(:db)
      allowed_sites = (Site.current) ? Site.current.id : User.current.sites

      sales_hash['amount'] = where(tracking_date: start_date..end_date, site_id: allowed_sites, state: 'confirmed', currency: currency).sum('amount').round(2)
      sales_hash['commission'] = where(tracking_date: start_date..end_date, site_id: allowed_sites, state: 'confirmed', currency: currency).sum('commission').round(2)

      if Site.current.present? && Site.current.commission_share_percentage > 0
        sales_hash['amount'] = sales_hash['amount'] * Site.current.commission_share_percentage / 100
        sales_hash['commission'] = sales_hash['commission'] * Site.current.commission_share_percentage / 100
      end

      sales_hash
    end

    def self.best_currency(currency_amounts)
      best_currency_hash = currency_amounts.max_by{|k,v| v}
      best_currency_key = best_currency_hash.first

      best_currency_key
    end
end
