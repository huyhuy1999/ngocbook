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
namespace MailPoetVendor\Doctrine\DBAL\Types;

use MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform;
/**
 * Type that maps an SQL boolean to a PHP boolean.
 *
 * @since 2.0
 */
class BooleanType extends \MailPoetVendor\Doctrine\DBAL\Types\Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return $platform->getBooleanTypeDeclarationSQL($fieldDeclaration);
    }
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return $platform->convertBooleansToDatabaseValue($value);
    }
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return $platform->convertFromBoolean($value);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return \MailPoetVendor\Doctrine\DBAL\Types\Type::BOOLEAN;
    }
    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return \PDO::PARAM_BOOL;
    }
}
