<?php

declare(strict_types=1);

namespace RevisionTen\CMS\EventListener;

use RevisionTen\CMS\Model\UserRead;
use RevisionTen\CMS\Model\Website;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class CurrentWebsiteListener
{
    /** @var SessionInterface  */
    private $session;

    /** @var Security  */
    private $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }

    /**
     * This method get the users chosen website and sets it on the request as "currentWebsite".
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if ($event->isMasterRequest()) {
            $request = $event->getRequest();

            // Path must begin with /admin, otherwise stop execution of this method.
            if (strpos($request->getRequestUri(), '/admin') !== 0) {
                return;
            }

            /** @var UserRead $user */
            $user = $this->security->getUser();

            if (null !== $user && null !== $request) {
                $websites = $user->getWebsites();

                if (null === $websites) {
                    throw new AccessDeniedHttpException('User does not belong to any website');
                }

                if ($websites->count() === 1) {
                    /** @var Website $currentWebsite */
                    $currentWebsite = $websites->first();
                    $currentWebsite = $currentWebsite->getId();
                } else {
                    $websiteIds = array_map(function ($website) {
                        /** @var Website $website */
                        return $website->getId();
                    }, $websites->toArray());

                    $currentWebsite = $request->cookies->get('cms_current_website') ?? $this->session->get('currentWebsite');

                    if (null === $currentWebsite || !\in_array($currentWebsite, $websiteIds, false)) {
                        // Current Website is null or does not exist in the users websites, set first website as current.
                        /** @var Website $currentWebsite */
                        $currentWebsite = $websites->first();
                        $currentWebsite = $currentWebsite->getId();
                    }
                }

                $request->request->set('currentWebsite', (int) $currentWebsite);
            }
        }
    }
}
