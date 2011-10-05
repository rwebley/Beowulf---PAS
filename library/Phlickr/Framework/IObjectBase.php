<?php
/**
 * @version $Id$
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * IObjectBase is the interface that defines the minimum retrieval methods an
 * ObjectBase should support
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @see         ObjectBase
 */
interface Phlickr_Framework_IObjectBase {
    /**
     * Returns the name of this object's getInfo API method.
     *
     * @return  string
     */
    static function getRequestMethodName();
    
    /**
     * Returns an array of parameters to be used when creating a
     * Phlickr_Request to call this object's getInfo API method.
     *
     * @param   string $id The id value of this object.
     * @return  array
     * @see     getId()
     */
    static function getRequestMethodParams($id);
}