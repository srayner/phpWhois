<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (c) 2015 Dmitry Lukashin
 */

namespace phpWhois\Handler;

use phpWhois\Provider\ProviderAbstract;
use phpWhois\Query;
use phpWhois\Response;

abstract class HandlerAbstract
{
    /**
     * @var ProviderAbstract Whois information provider
     */
    protected $provider;

    /**
     * @var Query
     */
    protected $query;

    /**
     * Handler constructor
     *
     * Each handler must inherit this method and set provider
     *
     * @param Query $query    Query for whois server
     */
    public function __construct(Query $query)
    {
        $this->setQuery($query);
    }

    /**
     * TODO: Set certain parser here
     */

    /**
     * Set query
     *
     * @param Query $query
     *
     * @return $this
     */
    protected function setQuery(Query $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set provider
     *
     * @param ProviderAbstract $provider
     *
     * @return $this
     */
    protected function setProvider(ProviderAbstract $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return ProviderAbstract
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Check if handler has all the necessary data assigned
     *
     * TODO: Probably this is redundant check
     *
     * @return bool
     */
    public function hasData()
    {
        return $this->getQuery()->hasData()
                && !is_null($this->getProvider());
    }

    /**
     * Perform a lookup of defined query
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function lookup()
    {
        if ($this->hasData()) {
            return $this->provider->lookup();
        } else {
            throw new \InvalidArgumentException('Handler doesn\'t have query or provider set');
        }
    }
}