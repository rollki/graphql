<?php

namespace Drupal\graphql\GraphQL\Type\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\graphql\GraphQL\Field\Entity\EntityIdField;
use Drupal\graphql\GraphQL\Field\Entity\EntityTypeField;
use Drupal\graphql\GraphQL\Relay\Field\GlobalIdField;
use Drupal\graphql\GraphQL\Relay\Type\NodeInterfaceType;
use Drupal\graphql\Utility\StringHelper;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

class EntityObjectType extends AbstractObjectType {
  /**
   * Creates an EntityObjectType instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entityType
   *   The entity type definition for this object type.
   */
  public function __construct(EntityTypeInterface $entityType) {
    $entityTypeId = $entityType->id();
    $typeName = StringHelper::formatTypeName($entityTypeId);

    $config = [
      'name' => "Entity{$typeName}",
      'interfaces' => [
        new NodeInterfaceType(),
        new EntityInterfaceType(),
        new EntitySpecificInterfaceType($entityType),
      ],
      'fields' => [
        'id' => new GlobalIdField($entityTypeId),
        'entityId' => new EntityIdField(),
        'entityType' => new EntityTypeField(),
        'placeholder' => [
          'type' => new StringType(),
        ],
      ],
    ];

    parent::__construct($config);
  }

  /**
   * {@inheritdoc}
   */
  public function build($config) {
    // @todo This method should not be required.
  }
}
