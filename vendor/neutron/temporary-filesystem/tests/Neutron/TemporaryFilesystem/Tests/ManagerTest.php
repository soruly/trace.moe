<?php

namespace Neutron\TemporaryFilesystem\Tests;

use Neutron\TemporaryFilesystem\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmptyFileAndCleanScope()
    {
        $basePath = 'basePath';
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/empty/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createEmptyFile')
            ->with($basePath, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/empty/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/empty/file', $manager->createEmptyFile($basePath, $prefix, $suffix, $extension, $maxTry));
        $manager->clean($prefix);
    }

    public function testCreateEmptyFileAndCleanOtherScope()
    {
        $basePath = 'basePath';
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->never())
            ->method('remove');

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createEmptyFile')
            ->with($basePath, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/empty/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/empty/file', $manager->createEmptyFile($basePath, $prefix, $suffix, $extension, $maxTry));
        $manager->clean('other prefix');
    }

    public function testCreateEmptyFileAndCleanScopes()
    {
        $basePath = 'basePath';
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/empty/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createEmptyFile')
            ->with($basePath, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/empty/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/empty/file', $manager->createEmptyFile($basePath, $prefix, $suffix, $extension, $maxTry));
        $manager->clean();
    }

    public function testCreateTemporaryDirectoryAndCleanScope()
    {
        $mode = 'mode';
        $prefix = 'prefix';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/dir'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryDirectory')
            ->with($mode, $maxTry, $prefix)
            ->will($this->returnValue('/path/to/dir'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/dir', $manager->createTemporaryDirectory($mode, $maxTry, $prefix));
        $manager->clean($prefix);
    }

    public function testCreateTemporaryDirectoryAndCleanOtherScope()
    {
        $mode = 'mode';
        $prefix = 'prefix';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->never())
            ->method('remove');

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryDirectory')
            ->with($mode, $maxTry, $prefix)
            ->will($this->returnValue('/path/to/dir'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/dir', $manager->createTemporaryDirectory($mode, $maxTry, $prefix));
        $manager->clean('other prefix');
    }

    public function testCreateTemporaryDirectoryAndCleanScopes()
    {
        $mode = 'mode';
        $prefix = 'prefix';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/dir'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryDirectory')
            ->with($mode, $maxTry, $prefix)
            ->will($this->returnValue('/path/to/dir'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/dir', $manager->createTemporaryDirectory($mode, $maxTry, $prefix));
        $manager->clean();
    }

    public function testCreateTemporaryFileAndCleanScope()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFile')
            ->with($prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/file', $manager->createTemporaryFile($prefix, $suffix, $extension, $maxTry));
        $manager->clean($prefix);
    }

    public function testCreateTemporaryFileAndCleanOtherScope()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->never())
            ->method('remove');

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFile')
            ->with($prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/file', $manager->createTemporaryFile($prefix, $suffix, $extension, $maxTry));
        $manager->clean('other prefix');
    }

    public function testCreateTemporaryFileAndCleanScopes()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFile')
            ->with($prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue('/path/to/file'));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals('/path/to/file', $manager->createTemporaryFile($prefix, $suffix, $extension, $maxTry));
        $manager->clean();
    }

    public function testCreateTemporaryFilesAndCleanScope()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';
        $quantity = 123;

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFiles')
            ->with($quantity, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue(array('/path/to/file')));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals(array('/path/to/file'), $manager->createTemporaryFiles($quantity, $prefix, $suffix, $extension, $maxTry));
        $manager->clean($prefix);
    }

    public function testCreateTemporaryFilesAndCleanOtherScope()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';
        $quantity = 123;

        $fs = $this->createFsMock();
        $fs->expects($this->never())
            ->method('remove');

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFiles')
            ->with($quantity, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue(array('/path/to/file')));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals(array('/path/to/file'), $manager->createTemporaryFiles($quantity, $prefix, $suffix, $extension, $maxTry));
        $manager->clean('other prefix');
    }

    public function testCreateTemporaryFilesAndCleanScopes()
    {
        $prefix = 'prefix';
        $suffix = 'suffix';
        $extension = 'extension';
        $maxTry = 'maxtry';
        $quantity = 123;

        $fs = $this->createFsMock();
        $fs->expects($this->once())
            ->method('remove')
            ->with(array('/path/to/file'));

        $tmpFs = $this->createTmpFsMock();
        $tmpFs->expects($this->once())
            ->method('createTemporaryFiles')
            ->with($quantity, $prefix, $suffix, $extension, $maxTry)
            ->will($this->returnValue(array('/path/to/file')));

        $manager = new Manager($tmpFs, $fs);
        $this->assertEquals(array('/path/to/file'), $manager->createTemporaryFiles($quantity, $prefix, $suffix, $extension, $maxTry));
        $manager->clean();
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Neutron\TemporaryFilesystem\Manager', Manager::create());
    }

    private function createTmpFsMock()
    {
        return $this->getMock('Neutron\TemporaryFilesystem\TemporaryFilesystemInterface');
    }

    private function createFsMock()
    {
        return $this
            ->getMockBuilder('Symfony\Component\Filesystem\Filesystem')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
