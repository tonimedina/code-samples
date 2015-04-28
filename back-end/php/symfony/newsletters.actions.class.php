<?php

require_once dirname( __FILE__ ) .'/../lib/newsletterGeneratorConfiguration.class.php';
require_once dirname( __FILE__ ) .'/../lib/newsletterGeneratorHelper.class.php';

/**
 * newsletter actions.
 *
 * @package    panacotta
 * @subpackage newsletter
 * @author     Toni Medina
 */
class newsletterActions extends autoNewsletterActions
{
  /**
   *
   * @param sfWebRequest $request
   */
  public function executeExportCSVData( sfWebRequest $request )
  {
    $data = Doctrine_Query::create()
      ->select( 'email, active, created_at' )
      ->from( 'Newsletter' )
      ->where( 'active < ?', 2 )
      ->setHydrationMode( Doctrine::HYDRATE_ARRAY )
      ->execute();

    $this->setLayout( false );

    $response = $this->getResponse();

    $response->clearHttpHeaders();
    $response->addCacheControlHttpHeader( 'Cache-control', 'must-revalidate, post-check=0, pre-check=0' );
    $response->setContentType( 'application/octet-stream', true );
    $response->setHttpHeader( 'Content-Transfer-Encoding', 'binary', true );
    $response->setHttpHeader( 'Content-Disposition', 'attachment; filename=newsletterdata.csv' );
    $response->sendHttpHeaders();

    echo '"email"; "active"; "registerdate"';
    echo "\n";

    foreach ( $data as $d )
    {
      echo '"'. $d['email'] .'";';
      echo '"'. ( ( $d['active'] ) ? 'ja' : 'nein' ) .'";';
      echo '"'. $d['created_at']. '"';
      echo "\n";
    }

    return sfView::NONE;
  }
}
