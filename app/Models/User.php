<?php
/**
 * NOTE: we need to add these docblocks below on every model
 * as Doctrine will read them using the PHP Reflection API
 * and will basically work out which table and columns
 * will need to look at etc.
 *
 * @todo Maybe worth looking into how we can add the properties for
 * each model and the docblocks when generating through the Cmder
 */
namespace App\Models;

use App\Models\Model;

/**
 * @Entity @Table(name="users")
 */
class User extends Model
{
    /**
     * @GeneratedValue(strategy="AUTO")
     * @Id @Column(name="id", type="integer", nullable=false)
     */
    protected $id;

    /**
     * @first_name @Column(type="string")
     */
    protected $first_name;

    /**
     * @last_name @Column(type="string")
     */
    protected $last_name;

    /**
     * @email @Column(type="string")
     */
    protected $email;

    /**
     * @password @Column(type="string")
     */
    protected $password;

    /**
     * @remember_token @Column(type="string")
     */
    protected $remember_token;

    /**
     * @remember_identifier @Column(type="string")
     */
    protected $remember_identifier;
}