<?php

namespace App\Entity;

use App\Entity\Setting;
use App\Entity\AllSkins;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @UniqueEntity(fields="email", message="user.email.not_unique")
 * @UniqueEntity(fields="pseudo", message="user.pseudo.not_unique")
 */
class User extends BaseEntity implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Groups({"user:read"})
     *
     * @Assert\NotBlank(
     *  message="user.email.not_blank"
     * )
     * @Assert\Email(
     *  message="user.email.not_valid"
     * )
     * @Assert\Length(
     *  max=180,
     *  maxMessage="user.email.too_long"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(
     *  message="user.password.not_blank"
     * )
     * @Assert\Length(
     *  min=8,
     *  minMessage="user.password.too_short",
     *  max=255,
     *  maxMessage="user.password.too_long"
     * )
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]/",
     *  match=true,
     *  message="user.password.not_valid"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     *
     * @Groups({"user:read"})
     *
     * @Assert\NotBlank(
     *  message="user.pseudo.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=30,
     *  minMessage="user.pseudo.too_short",
     *  maxMessage="user.pseudo.too_long"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read"})
     *
     * @Assert\NotBlank(
     *  message="user.money.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="user.money.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=30,
     *  message="user.money.too_high"
     * )
     */
    private $money = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read"})
     *
     * @Assert\NotBlank(
     *  message="user.level.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=1,
     *  message="user.level.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=30,
     *  message="user.level.too_high"
     * )
     */
    private $level = 1;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read"})
     *
     * @Assert\NotBlank(
     *  message="user.experience.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="user.experience.too_high"
     * )
     */
    private $experience = 0;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="user")
     *
     * @Groups({"user:read"})
     */
    private $games;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, inversedBy="users")
     *
     * @Groups({"user:read"})
     */
    private $badges;

    /**
     * @ORM\OneToOne(targetEntity=AllSkins::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"user:read"})
     */
    private $skins;

    /**
     * @ORM\OneToOne(targetEntity=Setting::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $setting;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->badges = new ArrayCollection();

        $this->setSkins(new AllSkins());
        $this->setSetting(new Setting());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(int $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setUser($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        $this->badges->removeElement($badge);

        return $this;
    }

    public function getSkins(): ?AllSkins
    {
        return $this->skins;
    }

    public function setSkins(AllSkins $skins): self
    {
        $this->skins = $skins;

        return $this;
    }

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    public function setSetting(Setting $setting): self
    {
        $this->setting = $setting;

        return $this;
    }
}
