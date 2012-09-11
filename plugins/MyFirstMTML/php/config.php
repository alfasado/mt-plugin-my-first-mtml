<?php
class MyFirstMTML extends MTPlugin {
    var $registry = array(
        'name' => 'MyFirstMTML',
        'id'   => 'MyFirstMTML',
        'key'  => 'myfirstmtml',
        'tags' => array(
            'block'    => array( 'SampleBlock' => '_hdlr_sample_block',
                                 'IfSampleBlockCond' => '_hdlr_if_sample_block_cond',
                                 ),
            'function' => array( 'SampleFunction1' => '_hdlr_sample_function_1',
                                 'SampleFunction2' => '_hdlr_sample_function_2',
                                 ),
            'modifier' => array( 'samplemodifier' => '_filter_sample_modifier',
                                 ),
        ),
    );

    function _hdlr_sample_block ( $args, $content, &$ctx, &$repeat ) {
        $localvars = array( '__counter__', '__out' );
        if (! isset( $content ) ) {
            $ctx->localize( $localvars );
            $vars =& $ctx->__stash['vars'];
            $from = $args[ 'from' ];
            $to = $args[ 'to' ];
            $glue = $args[ 'glue' ];
            if (! $from ) { $from = 1; }
            if (! $to ) { $to = 10; }
            if (! $glue ) $glue = ',';
            $ctx->stash( '__from__', $from );
            $ctx->stash( '__to__', $to );
            $ctx->stash( '__counter__', $from );
            $ctx->stash( '__glue__', $glue );
            $ctx->__stash[ 'vars' ][ '__counter__' ] = $from;
            $ctx->stash( 'foo', $from );
        } else {
            $to = $ctx->stash( '__to__' );
            $glue = $ctx->stash( '__glue__' );
            $out = $ctx->stash( '__out' );
            $counter = $ctx->__stash[ 'vars' ][ '__counter__' ] + 1;
            if ( $glue && $content && $out ) {
                $content = $glue . $content;
            } else {
                $ctx->stash( '__out', TRUE );
            }
            if ( $counter <= $to ) {
                $ctx->__stash[ 'vars' ][ '__counter__' ] = $counter;
                $ctx->stash( 'foo', $counter );
                $repeat = TRUE;
                return $content;
            } else {
                $ctx->restore( $localvars );
                $repeat = FALSE;
                return $content;
            }
        }
    }

    function _hdlr_if_sample_block_cond ( $args, $content, &$ctx, &$repeat ) {
        $rand = rand( 0, 1 );
        if ( $rand ) {
            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
        } else {
            return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, FALSE );
        }
    }
    
    function _hdlr_sample_function_1 ( $args, &$ctx ) {
        return 'Hello MTML!';
    }
    
    function _hdlr_sample_function_2 ( $args, &$ctx ) {
        $prefix = $args[ 'prefix' ];
        $suffix = $args[ 'suffix' ];
        $foo = $ctx->stash( 'foo' );
        if ( $prefix ) {
            $foo = $prefix . $foo;
        }
        if ( $suffix ) {
            $foo .= $suffix;
        }
        return $foo;
    }

    function _filter_sample_modifier ( $text, $arg ) {
        if (! $arg ) {
            $arg = '!!';
        }
        $text .= $arg;
        return $text;
    }

}

?>