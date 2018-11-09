<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserRead.
 *
 * @ORM\Entity
 * @ORM\Table("user")
 */
class UserRead implements UserInterface, \Serializable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $secret;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $resetToken;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatarUrl;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $version;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    private $devices;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    private $ips;

    /**
     * @var bool
     */
    private $imposter = false;

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return UserRead
     */
    public function setUuid(string $uuid): UserRead
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return UserRead
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return UserRead
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return UserRead
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     *
     * @return UserRead
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string|null $resetToken
     *
     * @return UserRead
     */
    public function setResetToken(string $resetToken = null): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        if (null === $this->color) {
            $this->color = $this->getColorFromUsername();
        }

        return $this->color;
    }

    /**
     * @param string|null $color
     *
     * @return UserRead
     */
    public function setColor(string $color = null): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    /**
     * @param string|null $avatarUrl
     *
     * @return UserRead
     */
    public function setAvatarUrl(string $avatarUrl = null): self
    {
        $this->color = $this->getColorFromUsername();
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getColorFromUsername(): string
    {
        $hue = $this->hue($this->getUsername().$this->getEmail());

        return substr($this->hsl2rgb($hue / 0xFFFFFFFF, 1, 0.9), 0, 7);
    }

    private function hsl2rgb($H, float $strength, float $saturation): string
    {
        $H *= 6;
        $h = (int) $H;
        $H -= $h;
        $saturation *= 255;
        $m = $saturation * (1 - $strength);
        $x = $saturation * (1 - $strength * (1 - $H));
        $y = $saturation * (1 - $strength * $H);
        $a = [[$saturation, $x, $m], [$y, $saturation, $m],
            [$m, $saturation, $x], [$m, $y, $saturation],
            [$x, $m, $saturation], [$saturation, $m, $y], ][$h];

        return sprintf('#%02X%02X%02X', $a[0], $a[1], $a[2]);
    }

    private function hue(string $string)
    {
        return unpack('L', hash('adler32', $string, true))[1];
    }

    /**
     * @return bool
     */
    public function isImposter(): bool
    {
        return $this->imposter;
    }

    /**
     * @param bool $imposter
     *
     * @return UserRead
     */
    public function setImposter(bool $imposter): self
    {
        $this->imposter = $imposter;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return UserRead
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDevices(): ?array
    {
        return $this->devices;
    }

    /**
     * @param array|null $devices
     *
     * @return UserRead
     */
    public function setDevices(array $devices = null): self
    {
        $this->devices = $devices;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getIps(): ?array
    {
        return $this->ips;
    }

    /**
     * @param array|null $ips
     *
     * @return UserRead
     */
    public function setIps(array $ips = null): self
    {
        $this->ips = $ips;

        return $this;
    }
}

\class_alias(UserRead::class, 'RevisionTen\CMS\Model\User');