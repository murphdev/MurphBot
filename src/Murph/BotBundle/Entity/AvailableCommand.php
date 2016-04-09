<?php

namespace Murph\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="available_command")
 */
class AvailableCommand
{
    /**
    * @ORM\Column(type="integer", name="id")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    */
    private $label;

    /**
    * @ORM\Column(type="text")
    */
    private $text;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    */
    private $showHelp = null;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return AvailableCommand
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return AvailableCommand
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set showHelp
     *
     * @param boolean $showHelp
     *
     * @return AvailableCommand
     */
    public function setShowHelp($showHelp)
    {
        $this->showHelp = $showHelp;

        return $this;
    }

    /**
     * Get showHelp
     *
     * @return boolean
     */
    public function getShowHelp()
    {
        return $this->showHelp;
    }
}
