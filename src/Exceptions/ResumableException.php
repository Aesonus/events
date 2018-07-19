<?php
/*
 * This file is part of the events package
 * 
 *  (c) Cory Laughlin <corylcomposinger@gmail.com>
 * 
 * For full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Aesonus\Events\Exceptions;

/**
 * Description of ResumableException
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class ResumableException extends \RuntimeException
{
    protected $listenerQueue;
    
    /**
     * Gets or sets the remaining listeners in the queue
     * @param array|null $listenerQueue
     */
    public function listeners(array $listenerQueue = null)
    {
        
    }
}
