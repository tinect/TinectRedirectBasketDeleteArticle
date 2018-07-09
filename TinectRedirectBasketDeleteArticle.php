<?php

namespace TinectRedirectBasketDeleteArticle;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

class TinectRedirectBasketDeleteArticle extends Plugin
{

    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    public function update(UpdateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    public static function getSubscribedEvents()
    {
        return ['Shopware_Controllers_Frontend_Checkout::deleteArticleAction::replace' => 'deleteArticle'];
    }

    /*
     * https://github.com/shopware/shopware/pull/1706
     */
    public function deleteArticle(\Enlight_Hook_HookArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Checkout $subject */
        $subject = $args->getSubject();

        $return = $args->getReturn();
        if ($subject->Request()->getParam('sDelete')) {
            Shopware()->Modules()->Basket()->sDeleteArticle($subject->Request()->getParam('sDelete'));
        }

        $subject->redirect(['action' => $subject->Request()->getParam('sTargetAction', 'index')]);
    }
}