<?php

namespace Cadulis\Sdk\Model;

class CustomerAssignmentRestriction
{
    public const USER_RESTRICTION_MODE_RESTRICT   = 'restrict';
    public const USER_RESTRICTION_MODE_PRIORITIZE = 'prioritize';

    protected string $restrictionMode      = '';
    protected array  $assignmentEmails     = [];
    protected bool   $withLastAssignedUser = false;

    public function setRestrictionMode(string $restrictionMode) : void
    {
        if (!in_array(
            $restrictionMode,
            ['', self::USER_RESTRICTION_MODE_RESTRICT, self::USER_RESTRICTION_MODE_PRIORITIZE]
        )
        ) {
            throw new \InvalidArgumentException('Invalid restriction mode');
        }
        $this->restrictionMode = $restrictionMode;
    }

    public function addAssignmentEmail(string $assignmentEmail) : void
    {
        $this->assignmentEmails[] = $assignmentEmail;
    }

    public function setWithLastAssignedUser(bool $withLastAssignedUser) : void
    {
        $this->withLastAssignedUser = $withLastAssignedUser;
    }

    public function getRestrictionMode() : string
    {
        return $this->restrictionMode;
    }

    public function getAssignmentEmails() : array
    {
        return $this->assignmentEmails;
    }

    public function isWithLastAssignedUser() : bool
    {
        return $this->withLastAssignedUser;
    }

    //#####          #####//

    /**
     * @param string $openingHours eg restrict;tech@cadulis.com;0
     */
    public function hydrate(string $assignmentRestriction)
    {
        if (empty(trim($assignmentRestriction))) {
            return;
        }
        $data = explode(';', $assignmentRestriction);
        if (count($data) === 1) {
            $this->setRestrictionMode(static::USER_RESTRICTION_MODE_RESTRICT);
            $this->addAssignmentEmail($data[0]);
            $this->setWithLastAssignedUser(0);
        }
        if (count($data) !== 3) {
            throw new \InvalidArgumentException('Invalid assignment restriction');
        }
        $this->setRestrictionMode($data[0]);
        $emails = explode(',', $data[1]);
        foreach ($emails as $email) {
            $this->addAssignmentEmail($email);
        }
        $this->setWithLastAssignedUser($data[2]);
    }

    /**
     * eg : restrict;tech@cadulis.com;0
     */
    public function toString() : string
    {
        return $this->restrictionMode
            . ';' . implode(',', $this->assignmentEmails)
            . ';' . ($this->withLastAssignedUser ? 1 : 0);
    }
}