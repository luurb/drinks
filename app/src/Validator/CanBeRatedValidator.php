<?php

namespace App\Validator;

use App\Entity\Drink;
use App\Entity\Rating;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security\Core\Security;

class CanBeRatedValidator extends ConstraintValidator
{
    public function __construct(
        private Security $security,
        private ManagerRegistry $doctrine
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\CanBeRated $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof Drink) {
            throw new \InvalidArgumentException('@CanBeRated constraint must be put on a property contating a Drink object');
        }

        $ratingRepo = $this->doctrine->getRepository(Rating::class);
        $rating = $ratingRepo->findOneBy(['drink' => $value]);

        if ($rating) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
