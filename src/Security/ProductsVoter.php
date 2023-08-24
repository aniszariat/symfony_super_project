<?php

namespace App\Security;

use App\Entity\Products;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
// use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter
{
    public const EDIT = 'PRODUCT_EDIT';
    public const DELETE = 'PRODUCT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }    protected function supports(string $attribute, $product): bool
    {
        if (!in_array($attribute, [self::EDIT,self::DELETE])) {
            return false;
        }
        if (!$product instanceof Products) {
            return false;
        }
        return true;

        //** other way :  return (in_array($attribute,[self::EDIT,self::DELETE]) && $product instanceof Products) */
    }

    public function voteOnAttribute(string $attribute, $product, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            // * on verfie s'il a le droit de modifier
            case self::EDIT:
                $this->canEdit();
                break;

                // * on verfie s'il a le droit de supprimer
            case self::DELETE:
                $this->canDelete();
                break;
        }
    }

    private function canEdit(): bool
    {
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }
    private function canDelete(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }


}
