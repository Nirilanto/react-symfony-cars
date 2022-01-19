<?php

namespace App\EventListener;


use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class UsersEventListener implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $hasher;
    private Security $security;

    public function __construct(UserPasswordHasherInterface $hasher, Security $security)
    {
        $this->hasher = $hasher;
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($args->getObject() instanceof User) {
            /** @var User $user */
            $user = $args->getObject();
            $password = $this->hasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->eraseCredentials();
        }

        if ($args->getObject() instanceof Comment) {
            $user = $this->security->getUser();
            /** @var Comment $comment */
            $comment = $args->getObject();
            $comment->setUsers($user);
        }
    }
}
