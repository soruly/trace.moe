<?php

namespace Tests\FFMpeg\Unit\Filters;

use FFMpeg\Filters\FiltersCollection;
use FFMpeg\Filters\Audio\SimpleFilter;
use Tests\FFMpeg\Unit\TestCase;

class FiltersCollectionTest extends TestCase
{
    public function testCount()
    {
        $coll = new FiltersCollection();
        $this->assertCount(0, $coll);

        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $this->assertCount(1, $coll);

        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $this->assertCount(2, $coll);
    }

    public function testIterator()
    {
        $coll = new FiltersCollection();
        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));
        $coll->add($this->getMock('FFMpeg\Filters\FilterInterface'));

        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
        $this->assertCount(2, $coll->getIterator());
    }

    public function testEmptyIterator()
    {
        $coll = new FiltersCollection();
        $this->assertInstanceOf('\ArrayIterator', $coll->getIterator());
    }

    public function testIteratorSort()
    {
        $coll = new FiltersCollection();
        $coll->add(new SimpleFilter(array('a')));
        $coll->add(new SimpleFilter(array('1'), 12));
        $coll->add(new SimpleFilter(array('b')));
        $coll->add(new SimpleFilter(array('2'), 12));
        $coll->add(new SimpleFilter(array('c')));
        $coll->add(new SimpleFilter(array('3'), 10));
        $coll->add(new SimpleFilter(array('d')));
        $coll->add(new SimpleFilter(array('4'), -2));
        $coll->add(new SimpleFilter(array('e')));

        $data = array();
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\AudioInterface');

        foreach ($coll as $filter) {
            $data = array_merge($data, $filter->apply($video, $format));
        }

        $this->assertEquals(array('1', '2', '3', 'a', 'b', 'c', 'd', 'e', '4'), $data);
    }
}
