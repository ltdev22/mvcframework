<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;
use App\Rules\RuleInterface;

class ExistsRule implements RuleInterface
{
    /**
     * The db instance.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $db;

    /**
     * Create new instance
     *
     * @param \Doctrine\ORM\EntityManager $db
     * @return  void
     */
    public function __construct(EntityManager $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     * @see  \App\Rules\RuleInterface
     */
    public function validate(string $field, string $value, array $params, array $fields): bool
    {
        // Query the database to see if there's already a record
        // with this specific $field => $value
       $result = $this->db->getRepository($params[0])
                            ->findOneBy([
                                $field => $value,
                            ]);

        // Check if record has been found
        return $result === null;
    }
}