<?php

namespace App\Validator;

use App\Entity\Drink;
use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CanBeReviewedValidator extends ConstraintValidator
{
    public function __construct(
        private Security $security,
        private ManagerRegistry $doctrine
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\CanBeReviewed $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof Drink) {
            throw new \InvalidArgumentException('@CanBeReviewed constraint must be put on a property contating a Drink object');
        }

        $reviewRepo = $this->doctrine->getRepository(Review::class);
        $review = $reviewRepo->findOneBy(['drink' => $value]);

        if ($review) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
