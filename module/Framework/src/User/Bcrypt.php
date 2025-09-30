<?php

namespace Framework\User;

use Traversable;
use Error;
use Exception;
use TypeError;

use function is_array;
use function mb_strlen;
use function microtime;
use function password_hash;
use function password_verify;
use function sprintf;
use function strtolower;
use function trigger_error;

use const E_USER_DEPRECATED;
use const PASSWORD_BCRYPT;
use const PHP_VERSION_ID;

/**
 * Bcrypt algorithm using crypt() function of PHP
 */
class Bcrypt
{
    public const MIN_SALT_SIZE = 22;

    /** @var string */
    protected $cost = '10';

    /** @var string */
    protected $salt;


    /**
     * Constructor
     *
     * @param array|Traversable $options
     */
    public function __construct($options = [])
    {
        if (! empty($options)) {
            if ($options instanceof Traversable) {
                $options = $this->iteratorToArray($options);
            }

            if (! is_array($options)) {
                throw new Exception(
                    'The options parameter must be an array or a Traversable'
                );
            }

            foreach ($options as $key => $value) {
                switch (strtolower($key)) {
                    case 'salt':
                        $this->setSalt($value);
                        break;
                    case 'cost':
                        $this->setCost($value);
                        break;
                }
            }
        }
    }


    /**
     * Converts an iterator to an array. The $recursive flag, on by default,
     * hints whether or not you want to do so recursively.
     *
     * @template TKey
     * @template TValue
     * @param  iterable<TKey, TValue> $iterator  The array or Traversable object to convert
     * @param  bool                   $recursive Recursively check all nested structures
     * @return array<TKey, TValue>
     */
    private function iteratorToArray($iterator, $recursive = true)
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if (! is_array($iterator) && ! $iterator instanceof Traversable) {
            throw new Exception(__METHOD__ . ' expects an array or Traversable object');
        }

        if (! $recursive) {
            if (is_array($iterator)) {
                return $iterator;
            }

            return iterator_to_array($iterator);
        }

        if (
            is_object($iterator)
            && ! $iterator instanceof \Iterator
            && method_exists($iterator, 'toArray')
        ) {
            /** @psalm-var array<TKey, TValue> $array */
            $array = $iterator->toArray();

            return $array;
        }

        $array = [];
        foreach ($iterator as $key => $value) {
            if (is_scalar($value)) {
                $array[$key] = $value;
                continue;
            }

            if ($value instanceof Traversable) {
                $array[$key] = static::iteratorToArray($value, $recursive);
                continue;
            }

            if (is_array($value)) {
                $array[$key] = static::iteratorToArray($value, $recursive);
                continue;
            }

            $array[$key] = $value;
        }

        /** @psalm-var array<TKey, TValue> $array */

        return $array;
    }


    /**
     * Bcrypt
     *
     * @param  string $password
     * @return string
     */
    public function create($password)
    {
        $options = ['cost' => (int) $this->cost];
        if (PHP_VERSION_ID < 70000) { // salt is deprecated from PHP 7.0
            $salt            = $this->salt ?: self::getBytes(self::MIN_SALT_SIZE);
            $options['salt'] = $salt;
        }
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }


    /**
     * Generate random bytes using different approaches
     * If PHP 7 is running we use the random_bytes() function
     *
     * @param  int $length
     * @return string
     */
    public static function getBytes($length)
    {
        try {
            return random_bytes($length);
        } catch (TypeError $e) {
            throw new Exception(
                'Invalid parameter provided to getBytes(length)',
                0,
                $e
            );
        } catch (Error $e) {
            throw new Exception(
                'The length must be a positive number in getBytes(length)',
                0,
                $e
            );
        }
    }


    /**
     * Verify if a password is correct against a hash value
     *
     * @param  string $password
     * @param  string $hash
     * @return bool
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Set the cost parameter
     *
     * @param  int|string $cost
     * @return Bcrypt Provides a fluent interface
     */
    public function setCost($cost)
    {
        if (! empty($cost)) {
            $cost = (int) $cost;
            if ($cost < 4 || $cost > 31) {
                throw new Exception(
                    'The cost parameter of bcrypt must be in range 04-31'
                );
            }
            $this->cost = sprintf('%1$02d', $cost);
        }
        return $this;
    }

    /**
     * Get the cost parameter
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set the salt value
     *
     * @param  string $salt
     * @return Bcrypt Provides a fluent interface
     */
    public function setSalt($salt)
    {
        if (PHP_VERSION_ID >= 70000) {
            trigger_error('Salt support is deprecated starting with PHP 7.0.0', E_USER_DEPRECATED);
        }

        if (mb_strlen($salt, '8bit') < self::MIN_SALT_SIZE) {
            throw new Exception(
                'The length of the salt must be at least ' . self::MIN_SALT_SIZE . ' bytes'
            );
        }

        $this->salt = $salt;
        return $this;
    }

    /**
     * Get the salt value
     *
     * @return string
     */
    public function getSalt()
    {
        if (PHP_VERSION_ID >= 70000) {
            trigger_error('Salt support is deprecated starting with PHP 7.0.0', E_USER_DEPRECATED);
        }

        return $this->salt;
    }

    /**
     * Benchmark the bcrypt hash generation to determine the cost parameter based on time to target.
     *
     * The default time to test is 50 milliseconds which is a good baseline for
     * systems handling interactive logins. If you increase the time, you will
     * get high cost with better security, but potentially expose your system
     * to DoS attacks.
     *
     * @see php.net/manual/en/function.password-hash.php#refsect1-function.password-hash-examples
     *
     * @param float $timeTarget Defaults to 50ms (0.05)
     * @return int Maximum cost value that falls within the time to target.
     */
    public function benchmarkCost($timeTarget = 0.05)
    {
        $cost = 8;

        do {
            $cost++;
            $start = microtime(true);
            password_hash('test', PASSWORD_BCRYPT, ['cost' => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return $cost;
    }
}
