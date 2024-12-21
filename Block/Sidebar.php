<?php
namespace Magiccart\Shopfranchise\Block;

class Sidebar extends Franchise
{
    public function getFranchises()
    {
        $collection = $this->getFranchiseCollection();
        $collection->setOrder("'order'", 'ASC');
        // $collection->setOrder('title','ASC');
        return $collection;
    }

}