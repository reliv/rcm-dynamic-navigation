<?php
/**
 * DoctrineAclRuleDataMapper.php
 *
 * DoctrineAclRuleDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Acl\Db;

use Doctrine\ORM\EntityManager;
use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Entity\AclRule;
use RcmUser\Acl\Entity\DoctrineAclRule;
use RcmUser\Db\DoctrineMapperInterface;
use RcmUser\Result;

/**
 * DoctrineAclRuleDataMapper
 *
 * DoctrineAclRuleDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DoctrineAclRuleDataMapper extends AclRuleDataMapper implements AclRuleDataMapperInterface, DoctrineMapperInterface
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var string $entityClass
     */
    protected $entityClass;

    /**
     * setEntityManager
     *
     * @param EntityManager $entityManager entityManager
     *
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * setEntityClass
     *
     * @param string $entityClass entityClass namespace
     *
     * @return void
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = (string)$entityClass;
    }

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * fetchAll
     *
     * @return Result
     */
    public function fetchAll()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT rule FROM ' . $this->getEntityClass() . ' rule '
            . 'INDEX BY rule.id'
        );

        $rules = $query->getResult();

        return new Result($rules);
    }

    /**
     * fetchByRole
     *
     * @param string $roleId the roleId
     *
     * @return Result
     */
    public function fetchByRole($roleId)
    {
        $rules = $this->getEntityManager()->getRepository(
            $this->getEntityClass()
        )->findBy(['roleId' => $roleId]);

        return new Result($rules);
    }

    /**
     * fetchByRule
     *
     * @param AclRule|string $rule rule
     *
     * @return Result|Result
     */
    public function fetchByRule($rule = AclRule::RULE_ALLOW)
    {
        $rules = $this->getEntityManager()->getRepository(
            $this->getEntityClass()
        )->findBy(['rule' => $rule]);

        return new Result($rules);
    }

    /**
     * fetchByResource
     *
     * @param string $resources Array of Resources
     *
     * @return Result
     */
    public function fetchByResources(Array $resources)
    {
        $ids = [];

        foreach ($resources as $resource) {
            if (!$resource instanceof AclResource) {
                continue;
            }

            $ids[] = $resource->getResourceId();
        }

        $query = $this->getEntityManager()->createQuery(
            'SELECT rule FROM ' . $this->getEntityClass() . ' rule '
            . 'INDEX BY rule.id ' . 'WHERE rule.resourceId IN (?1)'
        );

        $query->setParameter(
            1,
            $ids
        );

        $rules = $query->getResult();

        return new Result($rules);
    }

    /**
     * fetchByResource
     *
     * @param string $resourceId resourceId
     *
     * @return Result
     */
    public function fetchByResource($resourceId)
    {
        //$rules = $this->getEntityManager()->getRepository($this->getEntityClass())
        //    ->findBy(array('resourceId' => $resourceId));

        $query = $this->getEntityManager()->createQuery(
            'SELECT rule FROM ' . $this->getEntityClass() . ' rule '
            . 'INDEX BY rule.id ' . 'WHERE rule.resourceId = ?1'
        );

        $query->setParameter(
            1,
            $resourceId
        );

        $rules = $query->getResult();

        return new Result($rules);
    }

    /**
     * fetchByResourcePrivilege
     *
     * @param string $resourceId
     * @param mixed  $privilege
     *
     * @return Result|Result
     */
    public function fetchByResourcePrivilege($resourceId, $privilege)
    {
        if ($privilege === null) {

            $privQuery = 'AND rule.privilege is NULL';
        } else {

            $privQuery = 'AND rule.privilege = :privilege';
        }

        $query = $this->getEntityManager()->createQuery(
            'SELECT rule FROM ' . $this->getEntityClass() . ' rule ' .
            'INDEX BY rule.id ' .
            'WHERE rule.resourceId = :resourceId ' .
            $privQuery
        );

        $query->setParameter('resourceId', $resourceId);
        $query->setParameter('privilege', $privilege);

        $rules = $query->getResult();

        return new Result($rules);
    }

    /**
     * create
     *
     * @param AclRule $aclRule the aclRule
     *
     * @return Result
     */
    public function create(AclRule $aclRule)
    {
        $result = $this->getValidInstance($aclRule);

        $aclRule = $result->getData();

        $result = $this->read($aclRule);

        $existingAclRule = $result->getData();

        if ($result->isSuccess() && !empty($existingAclRule)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'Acl Role already exists: ' . var_export(
                    $aclRule,
                    true
                )
            );
        }

        // @todo if error, fail with null
        $this->getEntityManager()->persist($aclRule);
        $this->getEntityManager()->flush();

        return new Result($aclRule);
    }

    /**
     * read
     *
     * @param AclRule $aclRule the aclRule
     *
     * @return Result
     */
    public function read(AclRule $aclRule)
    {
        $rule = $aclRule->getRule();
        $roleId = $aclRule->getRoleId();
        $resourceId = $aclRule->getResourceId();
        $privilege = $aclRule->getPrivilege();

        // check required
        if (empty($rule) || empty($roleId) || empty($resourceId)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                "Rule could not be found by rule, roleId and resourceId."
            );
        }

        if ($privilege === null) {

            $privQuery = 'AND rule.privilege is NULL';
        } else {

            $privQuery = 'AND rule.privilege = ?4';
        }

        $query = $this->getEntityManager()->createQuery(
            'SELECT rule FROM ' . $this->getEntityClass() . ' rule '
            . 'WHERE rule.rule = ?1 ' . 'AND rule.roleId = ?2 '
            . 'AND rule.resourceId = ?3 ' . $privQuery
        );

        $query->setParameter(
            1,
            $rule
        );
        $query->setParameter(
            2,
            $roleId
        );
        $query->setParameter(
            3,
            $resourceId
        );
        if ($privilege !== null) {
            $query->setParameter(
                4,
                $privilege
            );
        }

        $rules = $query->getResult();

        if (empty($rules[0])) {
            return new Result([]);
        }

        return new Result($rules[0]);
    }

    /**
     * update
     *
     * @param AclRule $aclRule the aclRule
     *
     * @return Result
     */
    public function update(AclRule $aclRule)
    {
        // @todo write update method
        parent::update($aclRule);
    }

    /**
     * delete
     *
     * @param AclRule $aclRule the aclRule
     *
     * @return Result
     */
    public function delete(AclRule $aclRule)
    {
        $result = $this->read($aclRule);

        if (!$result->isSuccess()) {
            return $result;
        }

        $aclRule = $result->getData();

        $this->getEntityManager()->remove($aclRule);
        $this->getEntityManager()->flush();

        // @todo validate action
        return new Result(null, Result::CODE_SUCCESS);
    }

    /**
     * getValidInstance
     *
     * @param AclRule $aclRule aclRule
     *
     * @return Result
     */
    public function getValidInstance(AclRule $aclRule)
    {
        if (!($aclRule instanceof DoctrineAclRule)) {

            $doctrineAclRole = new DoctrineAclRule();
            $doctrineAclRole->populate($aclRule);

            $aclRule = $doctrineAclRole;
        }

        return new Result($aclRule);
    }
}
