<?php
/*!
 * Avalon
 * Copyright (C) 2011-2012 Jack Polgar
 *
 * This file is part of Avalon.
 *
 * Avalon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; version 3 only.
 *
 * Avalon is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Avalon. If not, see <http://www.gnu.org/licenses/>.
 */

namespace avalon;

/**
 * Database class.
 *
 * @author Jack P.
 * @package Avalon
 * @subpackage Core
 */
class Database
{
    private static $connections = array();
    private static $initiated = array();

    /**
     * Create a new database connection based off the passed
     * config array and the specified name.
     *
     * @param array $config
     * @param string $name
     *
     * @return object
     */
    public static function factory(array $config, $name = 'main')
    {
        // Make sure the connection name is available
        if (static::initiated($name)) {
            throw new Exception("Database connection name '{$name}' already initiated");
        }

        // Prepend 'DB_' to the driver name
        $class_name = "\\avalon\\database\\{$config['driver']}";

        // Create the connection and mark it as initiated.
        static::$connections[$name] = new $class_name($config, $name);
        static::$initiated[$name] = true;

        return static::$connections[$name];
    }

    /**
     * Returns the database instance object.
     *
     * @param string $name Connection name
     *
     * @return object
     */
    public static function connection($name = 'main')
    {
        return isset(static::$connections[$name]) ? static::$connections[$name] : false;
    }

    /**
     * Returns true if the database has been initiated, false if not.
     *
     * @param string $name Connection name
     *
     * @return bool
     */
    public static function initiated($name = 'main')
    {
        return !empty(static::$initiated[$name]);
    }
}
