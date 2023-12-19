<?php

namespace App\Services;

use App\Entity\Customer;
use App\Entity\Staff;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherService {

  public function __construct(
    private UserPasswordHasherInterface $uph
  )
  {
    // empty
  }

  public function hashUserPassword(Customer|Staff $user):Customer|Staff
  {
    $plainTextPwd = $user->getPassword();
    $hashedPwd = $this->uph->hashPassword(
        $user,
        $plainTextPwd,
    );
    $user->setPassword($hashedPwd);
    return $user;
  }
}