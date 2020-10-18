<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Entity\Contract;

/**
 * Interface RoomInterface
 *
 * @package Ares\Room\Entity\Contract
 */
interface RoomInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_OWNER_ID = 'owner_id';
    public const COLUMN_OWNER_NAME = 'owner_name';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_MODEL = 'model';
    public const COLUMN_PASSWORD = 'password';
    public const COLUMN_STATE = 'state';
    public const COLUMN_USERS = 'users';
    public const COLUMN_USERS_MAX = 'users_max';
    public const COLUMN_GUILD_ID = 'guild_id';
    public const COLUMN_CATEGORY = 'category';
    public const COLUMN_SCORE = 'score';
    public const COLUMN_PAPER_FLOOR = 'paper_floor';
    public const COLUMN_PAPER_WALL = 'paper_wall';
    public const COLUMN_PAPER_LANDSCAPE = 'paper_landscape';
    public const COLUMN_THICKNESS_WALL = 'thickness_wall';
    public const COLUMN_WALL_HEIGHT = 'wall_height';
    public const COLUMN_THICKNESS_FLOOR = 'thickness_floor';
    public const COLUMN_MOODLIGHT_DATA = 'moodlight_data';
    public const COLUMN_TAGS = 'tags';
    public const COLUMN_IS_PUBLIC = 'is_public';
    public const COLUMN_IS_STAFF_PICKED = 'is_staff_picked';
    public const COLUMN_ALLOW_OTHER_PETS = 'allow_other_pets';
    public const COLUMN_ALLOW_OTHER_PETS_EAT = 'allow_other_pets_eat';
    public const COLUMN_ALLOW_WALKTHROUGH = 'allow_walkthrough';
    public const COLUMN_ALLOW_HIDEWALL = 'allow_hidewall';
    public const COLUMN_CHAT_MODE = 'chat_mode';
    public const COLUMN_CHAT_WEIGHT = 'chat_weight';
    public const COLUMN_CHAT_SPEED = 'chat_speed';
    public const COLUMN_CHAT_HEARING_DISTANCE = 'chat_hearing_distance';
    public const COLUMN_CHAT_PROTECTION = 'chat_protection';
    public const COLUMN_OVERRIDE_MODEL = 'override_model';
    public const COLUMN_WHO_CAN_MUTE = 'who_can_mute';
    public const COLUMN_WHO_CAN_KICK = 'who_can_kick';
    public const COLUMN_WHO_CAN_BAN = 'who_can_ban';
    public const COLUMN_POLL_ID = 'poll_id';
    public const COLUMN_ROLLER_SPEED = 'roller_speed';
    public const COLUMN_PROMOTED = 'promoted';
    public const COLUMN_TRADE_MODE = 'trade_mode';
    public const COLUMN_MOVE_DIAGONALLY = 'move_diagonally';
    public const COLUMN_JUKEBOX_ACTIVE = 'jukebox_active';
    public const COLUMN_HIDEWIRED = 'hidewired';
    public const COLUMN_IS_CASINO = 'is_casino';
    public const COLUMN_IS_FROZEN = 'is_frozen';
}
