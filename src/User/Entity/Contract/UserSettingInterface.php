<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Contract;

/**
 * Interface UserSettingInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserSettingInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_CREDITS = 'credits';
    public const COLUMN_ACHIEVEMENT_SCORE = 'achievement_score';
    public const COLUMN_DAILY_RESPECT_POINTS = 'daily_respect_points';
    public const COLUMN_DAILY_PET_RESPECT_POINTS = 'daily_pet_respect_points';
    public const COLUMN_RESPECTS_GIVEN = 'respects_given';
    public const COLUMN_RESPECTS_RECEIVED = 'respects_received';
    public const COLUMN_GUILD_ID = 'guild_id';
    public const COLUMN_CAN_CHANGE_NAME = 'can_change_name';
    public const COLUMN_CAN_TRADE = 'can_trade';
    public const COLUMN_IS_CITIZEN = 'is_citizen';
    public const COLUMN_CITIZEN_LEVEL = 'citizen_level';
    public const COLUMN_HELPER_LEVEL = 'helper_level';
    public const COLUMN_TRADELOCK_AMOUNT = 'tradelock_amount';
    public const COLUMN_CFH_SEND = 'cfh_send';
    public const COLUMN_CFH_ABUSIVE = 'cfh_abusive';
    public const COLUMN_CFH_WARNINGS = 'cfh_warnings';
    public const COLUMN_CFH_BANS = 'cfh_bans';
    public const COLUMN_BLOCK_FOLLOWING = 'block_following';
    public const COLUMN_BLOCK_FRIENDREQUESTS = 'block_friendrequests';
    public const COLUMN_ROOMINVITES = 'block_roominvites';
    public const COLUMN_VOLUME_SYSTEM = 'volume_system';
    public const COLUMN_VOLUME_FURNI = 'volume_furni';
    public const COLUMN_VOLUME_TRAX = 'volume_trax';
    public const COLUMN_OLD_CHAT = 'old_chat';
    public const COLUMN_BLOCK_CAMERA_FOLLOW = 'block_camera_follow';
    public const COLUMN_CHAT_COLOR = 'chat_color';
    public const COLUMN_HOME_ROOM = 'home_room';
    public const COLUMN_ONLINE_TIME = 'online_time';
    public const COLUMN_TAGS = 'tags';
    public const COLUMN_CLUB_EXPIRE_TIMESTAMP = 'club_expire_timestamp';
    public const COLUMN_LOGIN_STREAK = 'login_streak';
    public const COLUMN_RENT_SPACE_ID = 'rent_space_id';
    public const COLUMN_RENT_SPACE_ENDTIME = 'rent_space_endtime';
    public const COLUMN_HOF_POINTS = 'hof_points';
    public const COLUMN_BLOCK_ALERTS = 'block_alerts';
    public const COLUMN_TALENT_TRACK_CITIZENSHIP_LEVEL = 'talent_track_citizenship_level';
    public const COLUMN_TALENT_TRACK_HELPERS_LEVEL = 'talent_track_helpers_level';
    public const COLUMN_IGNORE_BOTS = 'ignore_bots';
    public const COLUMN_IGNORE_PETS = 'ignore_pets';
    public const COLUMN_NUX = 'nux';
    public const COLUMN_MUTE_END_TIMESTAMP = 'mute_end_timestamp';
    public const COLUMN_ALLOW_NAME_CHANGE = 'allow_name_change';
    public const COLUMN_NAME_FORMAT = 'name_format';
    public const COLUMN_PERK_TRADE = 'perk_trade';
    public const COLUMN_FORUMS_POST_COUNT = 'forums_post_count';
    public const COLUMN_UI_FLAGS = 'ui_flags';
    public const COLUMN_HAS_GOTTEN_DEFAULT_SAVED_SEARCHES = 'has_gotten_default_saved_searches';
    public const COLUMN_BIO = 'bio';
    public const COLUMN_CLUB_NEXT_REWARD_TIMESTAMP = 'club_next_reward_timestamp';
    public const COLUMN_CLUB_GIFTS_LEFT = 'club_gifts_left';
    public const COLUMN_CLUB_GIFTS_CLAIMED = 'club_gifts_claimed';
}
