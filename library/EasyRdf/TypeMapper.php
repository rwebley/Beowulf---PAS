<?php

/**
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009 Nicholas J Humfrey.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright 
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. The name of the author 'Nicholas J Humfrey" may be used to endorse or 
 *    promote products derived from this software without specific prior 
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @version    $Id: TypeMapper.php 275 2009-12-22 00:01:56Z njh@aelius.com $
 */

/**
 * @see EasyRdf_Exception
 */
require_once "EasyRdf/Exception.php";

/**
 * Class to map between RDF Types and PHP Classes
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class EasyRdf_TypeMapper
{
    /** The type map registery */
    private static $_map = array();

    /** Get the registered class for an RDF type
     *
     * If a type is not registered, then this method will return null.
     *
     * @param  string  $type   The RDF type (e.g. foaf:name)
     * @return string          The class name (e.g. Model_Foaf_Name)
     */
    public static function get($type)
    {
        if (!is_string($type) or $type == null or $type == '') {
            throw new InvalidArgumentException(
                "\$type should be a string and cannot be null or empty"
            );
        } else if (array_key_exists($type, self::$_map)) {
            return self::$_map[$type];
        } else {
            return null;
        }
    }

    /** Register an RDF type with a PHP Class name
     *
     * @param  string  $type   The RDF type (e.g. foaf:name)
     * @param  string  $class  The PHP class name (e.g. Model_Foaf_Name)
     * @return string          The PHP class name
     */
    public static function set($type, $class)
    {
        if (!is_string($type) or $type == null or $type == '') {
            throw new InvalidArgumentException(
                "\$type should be a string and cannot be null or empty"
            );
        }

        if (!is_string($class) or $class == null or $class == '') {
            throw new InvalidArgumentException(
                "\$class should be a string and cannot be null or empty"
            );
        }
        
        return self::$_map[$type] = $class;
    }

}
