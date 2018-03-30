<?php

namespace Sprain\SwissQrBill\DataGroups;

use Sprain\SwissQrBill\DataGroups\Interfaces\QrCodeData;
use Sprain\SwissQrBill\Validator\Interfaces\Validatable;
use Sprain\SwissQrBill\Validator\ValidatorTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Address implements QrCodeData, Validatable
{
    use ValidatorTrait;

    /**
     * Name or company
     *
     * @var string
     */
    private $name;

    /**
     * Street / P.O. box of the creditor.
     *
     * May not include building or house number.
     *
     * @var string
     */
    private $street;

    /**
     * House number of the creditor
     *
     * @var string
     */
    private $houseNumber;

    /**
     * Postal code without county code
     *
     * @var string
     */
    private $postalCode;

    /**
     * City
     *
     * @var string
     */
    private $city;

    /**
     * Country (ISO 3166-1 alpha-2)
     *
     * @var string
     */
    private $country;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street = null) : self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber = null) : self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode) : self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city) : self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country) : self
    {
        $this->country = strtoupper($country);

        return $this;
    }

    public function getFullAddress() : string
    {
        $address = $this->getName();

        if ($this->getStreet()) {
            $address .= "\n" . $this->getStreet();

            if ($this->getHouseNumber()) {
                $address .= " " . $this->getHouseNumber();
            }
        }

        $address .= sprintf("\n%s-%s %s", $this->getCountry(), $this->getPostalCode(), $this->getCity());

        return $address;
    }

    public function getQrCodeData() : array
    {
        return [
            $this->getName(),
            $this->getStreet(),
            $this->getHouseNumber(),
            $this->getPostalCode(),
            $this->getCity(),
            $this->getCountry()
        ];
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new Assert\NotBlank(),
            new Assert\Length([
                'max' => 70
            ])
        ]);

        $metadata->addPropertyConstraints('street', [
            new Assert\Length([
                'max' => 70
            ])
        ]);

        $metadata->addPropertyConstraints('houseNumber', [
            new Assert\Length([
                'max' => 16
            ])
        ]);

        $metadata->addPropertyConstraints('postalCode', [
            new Assert\NotBlank(),
            new Assert\Length([
                'max' => 16
            ])
        ]);

        $metadata->addPropertyConstraints('city', [
            new Assert\NotBlank(),
            new Assert\Length([
                'max' => 35
            ])
        ]);

        $metadata->addPropertyConstraints('country', [
            new Assert\NotBlank(),
            new Assert\Country()
        ]);
    }
}