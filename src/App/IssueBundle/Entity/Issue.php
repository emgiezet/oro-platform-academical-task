<?php

namespace App\IssueBundle\Entity;

use App\IssueBundle\Model\ExtendIssue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

/**
 * Issue.
 *
 * @ORM\Table(name="app_issue")
 * @ORM\Entity(repositoryClass="App\IssueBundle\Entity\IssueRepository")
 * @Oro\Loggable
 * @Config(
 *      routeName="app_issue_index",
 *      routeView="app_issue_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-task"
 *          },
 *          "grouping"={
 *              "groups"={"dictionary"}
 *          },
 *          "dictionary"={
 *              "virtual_fields"={"id"},
 *              "search_fields"={"code", "summary", "type", "priority", "workflow", "resolution", "reporter", "assignee"},
 *              "representation_field"="fullName",
 *              "activity_support"="true"
 *          },
 *          "dataaudit"={"auditable"=true},
 *          "grid"={
 *              "default"="app-issue-grid"
 *          },
 *          "workflow"={
 *              "active_workflow"="app_issue_workflow"
 *          },
 *          "tag"={
 *              "enabled"=true
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue
{
    /**
     *
     */
    const TYPE_BUG = 0;

    /**
     *
     */
    const TYPE_TASK = 1;

    /**
     *
     */
    const TYPE_SUBTASK = 2;

    /**
     *
     */
    const TYPE_STORY = 3;

    /**
     * Array of available types used to build
     * @var array
     */
    public static $typeArray = array(
        self::TYPE_BUG => 'Bug',
        self::TYPE_TASK => 'Task',
        self::TYPE_SUBTASK => 'Subtask',
        self::TYPE_STORY => 'Story',
    );

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $type;

    /**
     * @var Priority
     * @ORM\ManyToOne(targetEntity="Priority", inversedBy="issues")
     * @ORM\JoinColumn(name="issue_priority_id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $priority;

    /**
     * @var Resolution|null
     * @ORM\ManyToOne(targetEntity="Resolution", inversedBy="issues")
     * @ORM\JoinColumn(name="issue_resolution_id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $resolution;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="issue_reporter_id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="issue_assigne_id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $asignee;

    /**
     * @var Issue[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="App\IssueBundle\Entity\Issue")
     * @ORM\JoinTable( name="app_issue_related",
     *     joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="related_id", referencedColumnName="id")}
     *      )
     * )
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $relatedIssues;

    /**
     * @var User[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable( name="app_issue_collaborators",
     *     joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * )
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $collaborators;

    /**
     * @var Issue|null
     *
     * @ORM\ManyToOne(targetEntity="App\IssueBundle\Entity\Issue", inversedBy="children")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $parent;

    /**
     * @var Issue[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\IssueBundle\Entity\Issue", mappedBy="parent")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $children;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    private $updated;

    /**
     * Issue constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
        $this->updated = new \DateTime('now');
        $this->collaborators = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return Resolution|null
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param Resolution|null $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param User $reporter
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * @return User
     */
    public function getAsignee()
    {
        return $this->asignee;
    }

    /**
     * @param User $asignee
     */
    public function setAsignee($asignee)
    {
        $this->asignee = $asignee;
    }

    /**
     * @return ArrayCollection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * @param ArrayCollection $relatedIssues
     */
    public function setRelatedIssues($relatedIssues)
    {
        $this->relatedIssues = $relatedIssues;
    }

    /**
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param ArrayCollection $collaborators
     */
    public function setCollaborators($collaborators)
    {
        $this->collaborators = $collaborators;
    }

    /**
     * @return Issue|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Issue|null $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return array
     */
    public static function getTypeArray()
    {
        return self::$typeArray;
    }

    /**
     * @param array $typeArray
     */
    public static function setTypeArray($typeArray)
    {
        self::$typeArray = $typeArray;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addCollaborator(User $user)
    {
        if (!$this->collaborators->contains($user)) {
            $this->collaborators->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function removeCollaborator(User $user)
    {
        if ($this->collaborators->contains($user)) {
            $this->collaborators->removeElement($user);
        }

        return $this;
    }

    /**
     * @param Issue $issue
     *
     * @return $this
     */
    public function addRelatedIssue(Issue $issue)
    {
        if (!$this->relatedIssues->contains($issue)) {
            $this->relatedIssues->add($issue);
        }

        return $this;
    }

    /**
     * @param Issue $issue
     *
     * @return $this
     */
    public function removeRelatedIssue(Issue $issue)
    {
        if ($this->relatedIssues->contains($issue)) {
            $this->relatedIssues->removeElement($issue);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->code.' - '.$this->summary;
    }
}
