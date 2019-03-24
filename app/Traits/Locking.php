<?php

namespace App\Traits;

use Sofa\ModelLocking\ModelLocked;
use Sofa\ModelLocking\ModelUnlocked;

trait Locking {
    use \Sofa\ModelLocking\Locking;

    /**
     * Lock the model and get lock token for further reference.
     *
     * Duration precedence:
     *   1. provided param
     *   2. lockable model property
     *   3. value from the config
     *   4. default 5 minutes
     *
     * @param  \DateTime|string $duration
     *                         DateTime|Carbon object or parsable date string @see strtotime()
     * @param  \Illuminate\Contracts\Auth\Authenticatable|integer|string $user
     *                         Identifier of the user who is locking the model
     * @return string
     */
    public function lock($duration = null, $user = null)
    {
        if ($duration instanceof Authenticatable) {
            list($user, $duration) = [$duration, null];
        }

        if (!$duration && property_exists($this, 'lock_duration')) {
            $duration = $this->lock_duration;
        }

        $lock = $this->modelLock()->firstOrNew([])->lock($duration, $user);

        $this->setRelation('modelLock', $lock);

        if ($events = $this->getEventDispatcher()) {
            //fixing vendors "fire" => "dispatch"
            $events->dispatch(new ModelLocked($this));
        }

        return $lock->getToken();
    }

    /**
     * Release the lock.
     *
     * @return $this
     */
    public function unlock()
    {
        $this->modelLock()->delete();

        unset($this->relations['modelLock']);

        if ($events = $this->getEventDispatcher()) {
            //fixing vendors "fire" => "dispatch"
            $events->dispatch(new ModelUnlocked($this));
        }

        return $this;
    }
}