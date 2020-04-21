<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Category
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property string $title
 * @property string|null $description
 * @property string|null $imagepath
 * @property boolean $publish
 * @property int|null $parent_id
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $subcategories
 * @property-read int|null $subcategories_count
 * @property-read string $full_image_path
 * @property-read int $value_for_trans_choice_subcategories
 * @property-read int $value_for_trans_choice_products
 * @property-read Category|null $parent
 * @property-read Collection|Product[] $products
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereAddedByUserId($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDescription($value)
 * @method static Builder|Category whereEditedByUserId($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereImagepath($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereParentSeeable($value)
 * @method static Builder|Category whereSeeable($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereSortOrder($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereUuid($value)
 * @mixin Eloquent
 * @property-read string $uc_title
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $publishedProducts
 * @property-read int|null $published_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $recursiveSubcategories
 * @property-read int|null $recursive_subcategories_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category wherePublish($value)
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read Collection|Permission[] $perms
 * @property-read int|null $perms_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereAddedByUserId($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static Builder|Role whereEditedByUserId($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Role extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int $id
 * @property int $customer_id
 * @property int $total_qty
 * @property int $total_payment
 * @property string $cart
 * @property int $status_id
 * @property string|null $comment
 * @property string|null $address
 * @property int|null $manager_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $customer
 * @property-read mixed $name
 * @property-read User|null $manager
 * @property-read Status $status
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAddress($value)
 * @method static Builder|Order whereCart($value)
 * @method static Builder|Order whereComment($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCustomerId($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereManagerId($value)
 * @method static Builder|Order whereStatusId($value)
 * @method static Builder|Order whereTotalPayment($value)
 * @method static Builder|Order whereTotalQty($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App{
/**
 * App\Status
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $style
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereDescription($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereStyle($value)
 * @method static Builder|Status whereTitle($value)
 * @method static Builder|Status whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Status extends \Eloquent {}
}

namespace App{
/**
 * App\Setting
 *
 * @property int $id
 * @property string $name
 * @property string $name_group
 * @property string $display_name
 * @property string $description
 * @property string $slug
 * @property string $group
 * @property string $type
 * @property string $permissible_values
 * @property string|null $value
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereDescription($value)
 * @method static Builder|Setting whereDisplayName($value)
 * @method static Builder|Setting whereEditedByUserId($value)
 * @method static Builder|Setting whereGroup($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereName($value)
 * @method static Builder|Setting whereNameGroup($value)
 * @method static Builder|Setting wherePermissibleValues($value)
 * @method static Builder|Setting whereSlug($value)
 * @method static Builder|Setting whereType($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereValue($value)
 * @mixin Eloquent
 */
	class Setting extends \Eloquent {}
}

namespace App{
/**
 * App\Tasksstatus
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $display_name
 * @property string|null $description
 * @property string $title
 * @property string $class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Tasksstatus newModelQuery()
 * @method static Builder|Tasksstatus newQuery()
 * @method static Builder|Tasksstatus query()
 * @method static Builder|Tasksstatus whereClass($value)
 * @method static Builder|Tasksstatus whereCreatedAt($value)
 * @method static Builder|Tasksstatus whereDescription($value)
 * @method static Builder|Tasksstatus whereDisplayName($value)
 * @method static Builder|Tasksstatus whereId($value)
 * @method static Builder|Tasksstatus whereName($value)
 * @method static Builder|Tasksstatus whereSlug($value)
 * @method static Builder|Tasksstatus whereTitle($value)
 * @method static Builder|Tasksstatus whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Tasksstatus extends \Eloquent {}
}

namespace App{
/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property string|null $slug
 * @property int $sort_order
 * @property int|null $manufacturer_id
 * @property string|null $vendor_code
 * @property number $promotional_price
 * @property $promotional_percentage
 * @property int|null $length
 * @property int|null $width
 * @property int|null $height
 * @property int|null $diameter
 * @property number $remaining
 * @property string|null $code_1c
 * @property int $category_id
 * @property boolean $publish
 * @property string|null $materials
 * @property string|null $description
 * @property string|null $modification
 * @property string|null $workingconditions
 * @property string|null $date_manufactured
 * @property float|null $price
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property int|null $count_views
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read mixed $short_description
 * @property-read Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read Manufacturer|null $manufacturer
 * @method static Builder|Product filter(Request $request, $filters = [])
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Product searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Product whereAddedByUserId($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDateManufactured($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereEditedByUserId($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereManufacturerId($value)
 * @method static Builder|Product whereMaterials($value)
 * @method static Builder|Product whereModification($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSeeable($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereSortOrder($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereViews($value)
 * @method static Builder|Product whereWorkingconditions($value)
 * @mixin Eloquent
 * @property-read string $full_image_path_l
 * @property-read string $full_image_path_m
 * @property-read string $full_image_path_s
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCode1c($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCountViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDiameter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePromotionalPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePromotionalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereVendorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWidth($value)
 */
	class Product extends \Eloquent {}
}

namespace App{
/**
 * App\Comment
 *
 * @property int $id
 * @property string|null $name
 * @property int $product_id
 * @property int $user_id
 * @property string $user_name
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read Product $product
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereName($value)
 * @method static Builder|Comment whereProductId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @method static Builder|Comment whereUserName($value)
 * @mixin Eloquent
 */
	class Comment extends \Eloquent {}
}

namespace App{
/**
 * App\Permission
 *
 * @property int $id
 * @property string $group
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereDescription($value)
 * @method static Builder|Permission whereDisplayName($value)
 * @method static Builder|Permission whereGroup($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Permission extends \Eloquent {}
}

namespace App{
/**
 * App\Image
 *
 * @property int $id
 * @property int $product_id
 * @property string $slug
 * @property string $path
 * @property string $name
 * @property string $ext
 * @property string $alt
 * @property int $sort_order
 * @property string $orig_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @method static Builder|Image whereAlt($value)
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereExt($value)
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereName($value)
 * @method static Builder|Image whereOrigName($value)
 * @method static Builder|Image wherePath($value)
 * @method static Builder|Image whereProductId($value)
 * @method static Builder|Image whereSlug($value)
 * @method static Builder|Image whereSortOrder($value)
 * @method static Builder|Image whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Image extends \Eloquent {}
}

namespace App{
/**
 * App\Taskspriority
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $display_name
 * @property string|null $description
 * @property string $title
 * @property string $class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Taskspriority newModelQuery()
 * @method static Builder|Taskspriority newQuery()
 * @method static Builder|Taskspriority query()
 * @method static Builder|Taskspriority whereClass($value)
 * @method static Builder|Taskspriority whereCreatedAt($value)
 * @method static Builder|Taskspriority whereDescription($value)
 * @method static Builder|Taskspriority whereDisplayName($value)
 * @method static Builder|Taskspriority whereId($value)
 * @method static Builder|Taskspriority whereName($value)
 * @method static Builder|Taskspriority whereSlug($value)
 * @method static Builder|Taskspriority whereTitle($value)
 * @method static Builder|Taskspriority whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class Taskspriority extends \Eloquent {}
}

namespace App{
/**
 * App\Customevent
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $model
 * @property int $model_id
 * @property string $model_name
 * @property mixed $type
 * @property string|null $description
 * @property string|null $details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Customevent filter(Request $request, $filters = [])
 * @method static Builder|Customevent newModelQuery()
 * @method static Builder|Customevent newQuery()
 * @method static Builder|Customevent query()
 * @method static Builder|Customevent whereCreatedAt($value)
 * @method static Builder|Customevent whereDescription($value)
 * @method static Builder|Customevent whereDetails($value)
 * @method static Builder|Customevent whereId($value)
 * @method static Builder|Customevent whereModel($value)
 * @method static Builder|Customevent whereModelId($value)
 * @method static Builder|Customevent whereModelName($value)
 * @method static Builder|Customevent whereType($value)
 * @method static Builder|Customevent whereUpdatedAt($value)
 * @method static Builder|Customevent whereUserId($value)
 * @mixin Eloquent
 */
	class Customevent extends \Eloquent {}
}

namespace App{
/**
 * App\Task
 *
 * @property int $id
 * @property int $master_user_id
 * @property int $slave_user_id
 * @property string $name
 * @property string $description
 * @property int $tasksstatus_id
 * @property int $taskspriority_id
 * @property string|null $comment_slave
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static \Illuminate\Database\Query\Builder|Task onlyTrashed()
 * @method static Builder|Task query()
 * @method static bool|null restore()
 * @method static Builder|Task whereAddedByUserId($value)
 * @method static Builder|Task whereCommentSlave($value)
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDeletedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereEditedByUserId($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereMasterUserId($value)
 * @method static Builder|Task whereName($value)
 * @method static Builder|Task whereSlaveUserId($value)
 * @method static Builder|Task whereTaskspriorityId($value)
 * @method static Builder|Task whereTasksstatusId($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withoutTrashed()
 * @mixin Eloquent
 */
	class Task extends \Eloquent {}
}

namespace App{
/**
 * App\Manufacturer
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property string|null $title
 * @property string|null $description
 * @property string|null $imagepath
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|Manufacturer newModelQuery()
 * @method static Builder|Manufacturer newQuery()
 * @method static Builder|Manufacturer query()
 * @method static Builder|Manufacturer whereAddedByUserId($value)
 * @method static Builder|Manufacturer whereCreatedAt($value)
 * @method static Builder|Manufacturer whereDescription($value)
 * @method static Builder|Manufacturer whereEditedByUserId($value)
 * @method static Builder|Manufacturer whereId($value)
 * @method static Builder|Manufacturer whereImagepath($value)
 * @method static Builder|Manufacturer whereName($value)
 * @method static Builder|Manufacturer whereSlug($value)
 * @method static Builder|Manufacturer whereSortOrder($value)
 * @method static Builder|Manufacturer whereTitle($value)
 * @method static Builder|Manufacturer whereUpdatedAt($value)
 * @method static Builder|Manufacturer whereUuid($value)
 * @mixin Eloquent
 */
	class Manufacturer extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int $status
 * @property string|null $verify_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static bool|null restore()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @method static Builder|User whereVerifyToken($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 */
	class User extends \Eloquent {}
}

