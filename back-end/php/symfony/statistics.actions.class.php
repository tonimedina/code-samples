<?php
require_once dirname( __FILE__ ) .'/../lib/statisticGeneratorConfiguration.class.php';
require_once dirname( __FILE__ ) .'/../lib/statisticGeneratorHelper.class.php';

/**
 * statistic actions.
 *
 * @package    panacotta
 * @subpackage statistic
 * @author     Toni Medina
 */
class statisticActions extends autoStatisticActions
{
  public $totals = array();

  public function executeIndex( sfWebRequest $request )
  {
    // sorting
    if ( $request->getParameter( 'sort' ) && $this->isValidSortColumn( $request->getParameter( 'sort' ) ) )
    {
      $this->setSort( array(
        $request->getParameter( 'sort' ),
        $request->getParameter( 'sortType' )
      ) );
    }

    // pager
    if ( $request->getParameter( 'page' ) )
    {
      $this->setPage( $request->getParameter( 'page' ) );
    }

    $this->aggregates = $this->totals;
    $this->pager      = $this->getPager();
    $this->sort       = $this->getSort();
  }

  protected function getPager()
  {
    $pager = $this->configuration->getPager( 'Statistic' );

    $pager->setQuery( $this->buildQuery() );
    $pager->setPage( $this->getPage() );
    $pager->init();

    return $pager;
  }

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();

    if ( null === $this->filters )
    {
      $this->filters = $this->configuration->getFilterForm( $this->getFilters() );
    }

    $this->filters->setTableMethod( $tableMethod );

    $query = $this->filters->buildQuery( $this->getFilters() );

    $this->_saveStatisticTotals( $query );
    $this->addSortQuery( $query );

    $event = $this->dispatcher->filter( new sfEvent( $this, 'admin.build_query' ), $query );
    $query = $event->getReturnValue();

    return $query;
  }

  public function executeFilter( sfWebRequest $request )
  {
    $this->setPage( 1 );

    if ( $request->hasParameter( '_reset' ) )
    {
      $this->setFilters( $this->configuration->getFilterDefaults() );
      $this->redirect( '@statistic' );
    }

    $this->filters = $this->configuration->getFilterForm( $this->getFilters() );

    $this->filters->bind( $request->getParameter( $this->filters->getName() ) );

    if ( $this->filters->isValid() )
    {
      $myFilter = $this->filters->getValues();

      $this->setFilters( $myFilter );
      $this->redirect( '@statistic' );
    }

    $this->aggregates = $this->totals;
    $this->pager      = $this->getPager();
    $this->sort       = $this->getSort();

    $this->setTemplate( 'index' );
  }

  public function executeListExportToCsv( sfWebRequest $request )
  {
    sfConfig::set( 'sf_web_debug', false );

    if ( $request->getParameter( 'sort' ) && $this->isValidSortColumn( $request->getParameter( 'sort' ) ) )
    {
      $this->setSort( array(
        $request->getParameter( 'sort' ),
        $request->getParameter( 'sortType' )
      ) );
    }

    $this->sort = $this->getSort();

    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader( 'Content-Type', 'application/vnd.ms-excel' );
    $this->getResponse()->setHttpHeader( 'Content-Disposition', 'attachment; filename=listExport.csv' );

    $query     = $this->buildQuery();
    $statistic = Doctrine_Core::getTable( 'Statistic' );

    $this->exportData = $statistic->getStatisticsByQuery( $query );

    $this->setLayout( false );
    $this->setTemplate( 'csvList' );
  }

  protected function _saveStatisticTotals( $q )
  {
    $query         = clone $q;
    $statistic     = Doctrine_Core::getTable( 'Statistic' );
    $totals        = $statistic->getTotalAggregates( $query );
    $this->totals  = $totals;
    $sumRevenue    = array();
    $sumCommission = array();

    foreach ( $totals as $total )
    {
      $sumRevenue[$total['currency']]    = ( isset( $total['sumRevenue'] ) ) ? $total['sumRevenue'] : 0;
      $sumCommission[$total['currency']] = ( isset( $total['sumCommission'] ) ) ? $total['sumCommission'] : 0;
    }

    $statistic->setRevenueTotal( $sumRevenue );
    $statistic->setCommissionTotal( $sumCommission );
  }
}
