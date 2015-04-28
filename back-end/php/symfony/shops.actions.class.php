<?php

require_once dirname( __FILE__ ) .'/../lib/shopGeneratorConfiguration.class.php';
require_once dirname( __FILE__ ) .'/../lib/shopGeneratorHelper.class.php';

/**
 * shop actions.
 *
 * @package    panacotta
 * @subpackage shop
 * @author     Toni Medina
 */
class shopActions extends autoShopActions
{
  public function executeEdit( sfWebRequest $request )
  {
    $this->shop    = $this->getRoute()->getObject();
    $this->tmpData = Doctrine_Query::create()
      ->select( 'fallbackUrl' )
      ->from( 'Shop' )
      ->where( 'id = ?', $this->shop->id )
      ->limit( 1 )
      ->execute();

    $this->shop->fallbackUrl = $this->tmpData[0]->fallbackUrl;
    $this->form              = $this->configuration->getForm( $this->shop );

    Varnish::purge();
  }

  public function executeOrder( sfWebRequest $request )
  {
    $this->shop    = $this->getRoute()->getObject();
    $this->coupons = $this->shop->getActiveCoupons();

    if ( $request->getParameter( 'clear' ) )
    {
      $this->shop->cleanOrder();

      $url = $this->generateUrl( 'shopOrder', $this->shop );

      $this->redirect( $url );
    }

    $this->tmpData = Doctrine_Query::create()
      ->select( 'fallbackUrl' )
      ->from( 'Shop' )
      ->where( 'id = ?', $this->shop->id )
      ->limit( 1 )
      ->execute();

    $this->shop->fallbackUrl = $this->tmpData[0]->fallbackUrl;

    Varnish::purge();
  }

  public function executeDelete( sfWebRequest $request )
  {
    $request->checkCSRFProtection();

    $this->dispatcher->notify( new sfEvent( $this, 'admin.delete_object', array(
      'object' => $this->getRoute()->getObject()
    ) ) );

    Doctrine_Query::create()
      ->delete( 'shopCategories' )
      ->where( 'shopId = ?', $request->getParameter( 'id' ) )
      ->execute();

    Doctrine_Query::create()
      ->delete( 'Coupon' )
      ->where( 'shopId = ?', $request->getParameter( 'id' ) )
      ->execute();

    if ( $this->getRoute()->getObject()->delete() )
    {
      $this->getUser()->setFlash( 'notice', 'The item was deleted successfully.' );
    }

    Varnish::purge();

    $this->redirect( '@shop' );
  }

  /**
  * catches the Ajax request and updates the order of the coupons
  * @param sfWebRequest $request
  */
  public function executeUpdateOrder( sfWebRequest $request )
  {
    $this->forward404Unless( $request->isXmlHttpRequest() );
    $request->checkCSRFProtection();

    $this->shop = $this->getRoute()->getObject();
    $order      = $request->getParameter( 'listItem' );

    $this->shop->cleanOrder();

    if ( !empty( $order ) )
    {
      foreach ( $order as $index => $item )
      {
        Doctrine_Query::create()
          ->update( 'Coupon c' )
          ->set( 'c.order_position', $index )
          ->where( 'id = ?', $item )
          ->execute();
      }
    }
  }

  protected function processForm( sfWebRequest $request, sfForm $form )
  {
    $form->bind( $request->getParameter( $form->getName() ), $request->getFiles( $form->getName() ) );

    if ( $form->isValid() )
    {
      Varnish::purge();

      $notice = ( $form->getObject()->isNew() ) ? 'The item was created successfully.' : 'The item was updated successfully.';
      $isNew  = ( $form->getObject()->isNew() ) ? true : false;

      try
      {
        $shop = $form->save();

        /* START update categories  */
        if ( !$isNew )
        {
          Doctrine_Query::create()
            ->delete()
            ->from( 'shopCategories' )
            ->where( 'shopId = ?', $shop->getId() )
            ->execute();
        }

        $shop->save();

        $params = $request->getParameter( $form->getName() );

        for ( $i = 0; $i < count( $params['categoryId'] ); $i++ )
        {
          $shopCategory             = new shopCategories();
          $shopCategory->categoryId = $params['categoryId'][$i];
          $shopCategory->shopId     = $shop->getId();
          
          $shopCategory->save();
        }
      }
      catch ( Doctrine_Validator_Exception $e )
      {
        $errorStack = $form->getObject()->getErrorStack();
        $message    = get_class( $form->getObject() ) . ' has ' . count( $errorStack ) . " field" . ( count( $errorStack ) > 1 ?  's' : null ) . " with validation errors: ";

        foreach ( $errorStack as $field => $errors )
        {
          $message .= "$field (" . implode( ", ", $errors ) . "), ";
        }

        $message = trim( $message, ', ' );

        $this->getUser()->setFlash( 'error', $message );

        return sfView::SUCCESS;
      }

      $this->dispatcher->notify( new sfEvent( $this, 'admin.save_object', array( 'object' => $shop ) ) );

      if ( $request->hasParameter( '_save_and_add' ) )
      {
        $this->getUser()->setFlash( 'notice', $notice.' You can add another one below.' );
        $this->redirect( '@shop_new' );
      }
      else
      {
        $this->getUser()->setFlash( 'notice', $notice );
        $this->redirect( array(
          'sf_route' => 'shop_edit',
          'sf_subject' => $shop )
        );
      }
    }
    else
    {
      $this->getUser()->setFlash( 'error', 'The item has not been saved due to some errors.', false );
    }
  }
}
