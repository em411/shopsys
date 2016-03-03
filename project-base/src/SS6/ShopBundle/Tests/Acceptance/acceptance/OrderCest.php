<?php

namespace SS6\ShopBundle\Tests\Acceptance\acceptance;

use SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\Front\OrderPage;
use SS6\ShopBundle\Tests\Acceptance\acceptance\PageObject\Front\ProductListPage;
use SS6\ShopBundle\Tests\Test\Codeception\AcceptanceTester;

class OrderCest {

	public function testFormRemembersPaymentAndTransportWhenClickingBack(
		ProductListPage $productListPage,
		OrderPage $orderPage,
		AcceptanceTester $me
	) {
		$me->wantTo('have my payment and transport remebered by order');

		$me->amOnPage('/televize-audio/');
		$productListPage->addProductToCartByName('Defender 2.0 SPK-480');
		$me->clickByText('Přejít do košíku');
		$me->clickByText('Objednat');

		$orderPage->assertCzechPostTransportIsNotSelected();
		$orderPage->selectCzechPostTransport();
		$orderPage->assertCashOnDeliveryPaymentIsNotSelected();
		$orderPage->selectCashOnDeliveryPayment();
		$me->clickByText('Pokračovat v objednávce');
		$me->clickByText('Zpět na výběr dopravy a platby');

		$orderPage->assertCzechPostTransportIsSelected();
		$orderPage->assertCashOnDeliveryPaymentIsSelected();
	}

	public function testFormRemembersPaymentAndTransportWhenGoingDirectlyToUrl(
		ProductListPage $productListPage,
		OrderPage $orderPage,
		AcceptanceTester $me
	) {
		$me->wantTo('have my payment and transport remebered by order');

		$me->amOnPage('/televize-audio/');
		$productListPage->addProductToCartByName('Defender 2.0 SPK-480');
		$me->clickByText('Přejít do košíku');
		$me->clickByText('Objednat');

		$orderPage->assertCzechPostTransportIsNotSelected();
		$orderPage->selectCzechPostTransport();
		$orderPage->assertCashOnDeliveryPaymentIsNotSelected();
		$orderPage->selectCashOnDeliveryPayment();
		$me->clickByText('Pokračovat v objednávce');
		$me->amOnPage('/objednavka/');

		$orderPage->assertCzechPostTransportIsSelected();
		$orderPage->assertCashOnDeliveryPaymentIsSelected();
	}

	public function testFormRemembersFirstName(ProductListPage $productListPage, OrderPage $orderPage, AcceptanceTester $me) {
		$me->wantTo('have my first name remebered by order');

		$me->amOnPage('/televize-audio/');
		$productListPage->addProductToCartByName('Defender 2.0 SPK-480');
		$me->clickByText('Přejít do košíku');
		$me->clickByText('Objednat');
		$orderPage->selectCzechPostTransport();
		$orderPage->selectCashOnDeliveryPayment();
		$me->clickByText('Pokračovat v objednávce');

		$orderPage->fillFirstName('Jan');
		$me->clickByText('Zpět na výběr dopravy a platby');
		$me->amOnPage('/objednavka/');
		$me->clickByText('Pokračovat v objednávce');

		$orderPage->assertFirstNameIsFilled('Jan');
	}

}
