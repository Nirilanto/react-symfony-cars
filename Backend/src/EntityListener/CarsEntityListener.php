<?php

namespace App\EntityListener;


use App\Entity\Cars;
use Symfony\Component\Security\Core\Security;

class CarsEntityListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function postLoad(Cars $cars)
    {
        $user = $this->security->getUser();

        if (!$user) {
            $cars->setCommentEmpty();
        }

    }
}
