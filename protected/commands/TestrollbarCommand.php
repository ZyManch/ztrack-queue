<?php

/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 10.04.2015
 * Time: 17:05
 */
class TestrollbarCommand extends AbstractCommand {

    public function run() {

        $demo = array(
            "access_token" => "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
            "data"         => array(
                "environment"  => "master",
                "body"         => array(

                    "trace"        => array(
                        "frames"    => array(
                            array(
                                "filename" => "/Users/brian/www/mox/mox/views/project.py",
                                "lineno"   => rand(24,27),
                                "colno"    => 8,
                                "method"   => "index",
                                "code"     => "_save_last_project(request, project_id, force=True)",
                                "context"  => array(
                                    "pre"  => array(
                                        "project = request.context", "project_id = project.id"
                                    ),
                                    "post" => array()
                                ),
                                "args"     => array("<Request object>", 25),

                            ),
                            array(
                                "filename" => "/Users/brian/www/mox/mox/views/project.py", "lineno" => 497,
                                "method"   => "_save_last_project", "code" => "user = foo"
                            )
                        ),

                        "exception" => array(
                            "class"       => "NameError",
                            "message"     => "global name 'foo' is not defined",
                            "description" => "Something went wrong while trying to save the user object"
                        )

                    ),
                    "message"      => array(

                        "body"  => "Request over threshold of 10 seconds",
                        "route" => "home#index", "time_elapsed" => 15.23

                    ),
                    "crash_report" => array(
                        "raw" => "<crash report here>"
                    )

                ),
                "level"        => "error",
                "timestamp"    => time(),
                "platform"     => "linux",
                "language"     => "python",
                "context"      => "project#index",
                "request"      => array(
                    "url"          => "https://rollbar.com/project/1",
                    "method"       => "GET",
                    "headers"      => array(
                        "Accept" => "text/html", "Referer" => "https://rollbar.com/"
                    ),
                    "params"       => array(
                        "controller" => "project", "action" => "index"
                    ),
                    "GET"          => array(),
                    "query_string" => "",
                    "POST"         => array(),
                    "body"         => "",
                    "user_ip"      => rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255)

                ),

                "person"       => array(
                    "id"       => "12345",
                    "username" => "brianr",
                    "email"    => "brian@rollbar.com"
                ),

                "server"       => array(
                    "host"         => "web4",
                    "root"         => "/Users/brian/www/mox",
                    "branch"       => "master",
                    "code_version" => "b6437f45b7bbbb15f5eddc2eace4c71a8625da8c",
                    "sha"          => "b6437f45b7bbbb15f5eddc2eace4c71a8625da8c"
                ),

                "client"       => array(
                    "javascript" => array(
                        "browser"               => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36 OPR/20.0.1387.82",
                        "code_version"          => "b6437f45b7bbbb15f5eddc2eace4c71a8625da8c",
                        "source_map_enabled"    => false,
                        "guess_uncaught_frames" => false
                    )
                ),
                "custom"       => array(),
                "title"        => "NameError when setting last project in views/project.py",
            )
        );
        $queue = new QueueAccessor($this->_config['error_queue'], ErrorControllerQueue::QUEUE_NAME);
        $queue->push(array(
            'engine'=>ErrorControllerQueue::ENGINE_ROLLBAR,
            'token' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'result' => json_encode($demo)
        ));
    }

}