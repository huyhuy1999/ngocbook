<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
namespace MailPoetVendor\Doctrine\Common\Persistence\Mapping\Driver;

use MailPoetVendor\Doctrine\Common\Persistence\Mapping\ClassMetadata;
use MailPoetVendor\Doctrine\Common\Persistence\Mapping\MappingException;
/**
 * The StaticPHPDriver calls a static loadMetadata() method on your entity
 * classes where you can manually populate the ClassMetadata instance.
 *
 * @link   www.doctrine-project.org
 * @since  2.2
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 */
class StaticPHPDriver implements \MailPoetVendor\Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
{
    /**
     * Paths of entity directories.
     *
     * @var array
     */
    private $paths = [];
    /**
     * Map of all class names.
     *
     * @var array
     */
    private $classNames;
    /**
     * Constructor.
     *
     * @param array|string $paths
     */
    public function __construct($paths)
    {
        $this->addPaths((array) $paths);
    }
    /**
     * Adds paths.
     *
     * @param array $paths
     *
     * @return void
     */
    public function addPaths(array $paths)
    {
        $this->paths = \array_unique(\array_merge($this->paths, $paths));
    }
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className, \MailPoetVendor\Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata)
    {
        $className::loadMetadata($metadata);
    }
    /**
     * {@inheritDoc}
     * @todo Same code exists in AnnotationDriver, should we re-use it somehow or not worry about it?
     */
    public function getAllClassNames()
    {
        if ($this->classNames !== null) {
            return $this->classNames;
        }
        if (!$this->paths) {
            throw \MailPoetVendor\Doctrine\Common\Persistence\Mapping\MappingException::pathRequired();
        }
        $classes = [];
        $includedFiles = [];
        foreach ($this->paths as $path) {
            if (!\is_dir($path)) {
                throw \MailPoetVendor\Doctrine\Common\Persistence\Mapping\MappingException::fileMappingDriversRequireConfiguredDirectoryPath($path);
            }
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($iterator as $file) {
                if ($file->getBasename('.php') == $file->getBasename()) {
                    continue;
                }
                $sourceFile = \realpath($file->getPathName());
                require_once $sourceFile;
                $includedFiles[] = $sourceFile;
            }
        }
        $declared = \get_declared_classes();
        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            if (\in_array($sourceFile, $includedFiles) && !$this->isTransient($className)) {
                $classes[] = $className;
            }
        }
        $this->classNames = $classes;
        return $classes;
    }
    /**
     * {@inheritdoc}
     */
    public function isTransient($className)
    {
        return !\method_exists($className, 'loadMetadata');
    }
}
