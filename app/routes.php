<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Enables Lazy CORS - Preflight Request
    $app->options('/{routes:.+}', function ($request, $response, $arguments) {
        return $response;
    });

    // Status
    $app->get('/', \Ares\Framework\Controller\Status\StatusController::class . ':getStatus');

    $app->group('/{locale}', function (RouteCollectorProxy $group) {

        // Only Accessible if LoggedIn
        $group->group('', function ($group) {
            // User
            $group->group('/user', function ($group) {
                $group->get('', \Ares\User\Controller\UserController::class . ':user');
                $group->post('/ticket', \Ares\User\Controller\AuthController::class . ':ticket');
                $group->post('/locale', \Ares\User\Controller\UserController::class . ':updateLocale');
                $group->get('/gift', \Ares\User\Controller\Gift\DailyGiftController::class . ':pick');
                $group->post('/currency', \Ares\User\Controller\UserCurrencyController::class . ':update');
                $group->group('/settings', function ($group) {
                    $group->post('/change_general_settings', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeGeneralSettings');
                    $group->post('/change_password', \Ares\User\Controller\Settings\UserSettingsController::class . ':changePassword');
                    $group->post('/change_email', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeEmail');
                    $group->post('/change_username', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeUsername');
                });
            });

            // Articles
            $group->group('/articles', function ($group) {
                $group->post('/create', \Ares\Article\Controller\ArticleController::class . ':create')
                    ->setName('create-article');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Article\Controller\ArticleController::class . ':list');
                $group->get('/pinned', \Ares\Article\Controller\ArticleController::class . ':pinned');
                $group->get('/{slug}', \Ares\Article\Controller\ArticleController::class . ':article');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':delete')
                    ->setName('delete-article');
            });

            // Comments
            $group->group('/comments', function ($group) {
                $group->post('/create', \Ares\Article\Controller\CommentController::class . ':create');
                $group->post('/edit', \Ares\Article\Controller\CommentController::class . ':edit')
                    ->setName('edit-article-comment');
                $group->get('/{article_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Article\Controller\CommentController::class . ':list');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\CommentController::class . ':delete')
                    ->setName('delete-article-comment');
            });

            // Votes
            $group->group('/votes', function ($group) {
                $group->post('/create', \Ares\Vote\Controller\VoteController::class . ':create');
                $group->get('/total', \Ares\Vote\Controller\VoteController::class . ':getTotalVotes');
                $group->post('/delete', \Ares\Vote\Controller\VoteController::class . ':delete');
            });

            // Community
            $group->group('/community', function ($group) {
                $group->get('/search/{term}', \Ares\Community\Controller\CommunityController::class . ':search');
            });

            // Guilds
            $group->group('/guilds', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guild\Controller\GuildController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':guild');
                $group->get('/members/{id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guild\Controller\GuildController::class . ':members');
                $group->get('/most/members', \Ares\Guild\Controller\GuildController::class . ':mostMembers');
            });

            // Guestbook Entries
            $group->group('/guestbook', function ($group) {
                $group->post('/create', \Ares\Guestbook\Controller\GuestbookController::class . ':create');
                $group->get('/profile/{profile_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guestbook\Controller\GuestbookController::class . ':profileList');
                $group->get('/guild/{guild_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guestbook\Controller\GuestbookController::class . ':guildList');
                $group->delete('/{id:[0-9]+}', \Ares\Guestbook\Controller\GuestbookController::class . ':delete')
                    ->setName('delete-guestbook-entry');
            });

            // Friends
            $group->group('/friends', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Messenger\Controller\MessengerController::class . ':friends');
            });

            // Rooms
            $group->group('/rooms', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':room');
                $group->get('/most/visited', \Ares\Room\Controller\RoomController::class . ':mostVisited');
            });

            // Hall-Of-Fame
            $group->group('/hall-of-fame', function ($group) {
                $group->get('/top-credits', \Ares\User\Controller\UserHallOfFameController::class . ':topCredits');
                $group->get('/top-diamonds', \Ares\User\Controller\UserHallOfFameController::class . ':topDiamonds');
                $group->get('/top-pixels', \Ares\User\Controller\UserHallOfFameController::class . ':topPixels');
                $group->get('/top-online-time',
                    \Ares\User\Controller\UserHallOfFameController::class . ':topOnlineTime');
                $group->get('/top-achievement',
                    \Ares\User\Controller\UserHallOfFameController::class . ':topAchievement');
            });

            // Photos
            $group->group('/photos', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Photo\Controller\PhotoController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':photo');
                $group->post('/search', \Ares\Photo\Controller\PhotoController::class . ':search');
                $group->delete('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':delete')
                    ->setName('delete-photo');
            });

            // Profiles
            $group->group('/profiles', function ($group) {
                $group->get('/{profile_id:[0-9]+}/badges/slot',
                    \Ares\Profile\Controller\ProfileController::class . ':slotBadges');
                $group->get('/{profile_id:[0-9]+}/badges/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':badgeList');
                $group->get('/{profile_id:[0-9]+}/friends/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':friendList');
                $group->get('/{profile_id:[0-9]+}/guilds/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':guildList');
                $group->get('/{profile_id:[0-9]+}/rooms/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':roomList');
                $group->get('/{profile_id:[0-9]+}/photos/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':photoList');
            });

            // Permissions
            $group->group('/permissions', function ($group) {
                $group->get('/rank/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Permission\Controller\PermissionController::class . ':listUserWithRank');
            });

            // Payments
            $group->group('/payments', function ($group) {
                $group->post('/create', \Ares\Payment\Controller\PaymentController::class . ':create');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':payment');
                $group->delete('/{id:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':delete')
                    ->setName('delete-payment');
            });

            // Forum
            $group->group('/forum', function ($group) {
                $group->group('/comments', function ($group) {
                    $group->post('/{thread:[0-9]+}/create', \Ares\Forum\Controller\CommentController::class . ':create');
                    $group->get('/{thread:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\CommentController::class . ':list');
                });
                $group->group('/topics', function ($group) {
                    $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\TopicController::class . ':list');
                });
                $group->group('/threads', function ($group) {
                    $group->get('/{topic_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\ThreadController::class . ':list');
                    $group->get('/{topic_id:[0-9]+}/{slug}', \Ares\Forum\Controller\ThreadController::class . ':thread');
                });
            });

            // De-Authentication
            $group->post('/logout', \Ares\User\Controller\AuthController::class . ':logout');
        })->add(\Ares\Role\Middleware\RolePermissionMiddleware::class)
        ->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        // Authentication
        $group->post('/login', \Ares\User\Controller\AuthController::class . ':login');
        $group->group('/register', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':register');
            $group->get('/looks', \Ares\User\Controller\AuthController::class . ':viableLooks');
        });

        // Global Settings
        $group->group('/settings', function ($group) {
            $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                \Ares\Settings\Controller\SettingsController::class . ':list');
            $group->post('/get', \Ares\Settings\Controller\SettingsController::class . ':get');
            $group->post('/set', \Ares\Settings\Controller\SettingsController::class . ':set')
                ->setName('set-global-setting');
        });

        // Global Routes
        $group->get('/user/online', \Ares\User\Controller\UserController::class . ':onlineUser');
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class)
        ->add(\Ares\Framework\Middleware\ThrottleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
