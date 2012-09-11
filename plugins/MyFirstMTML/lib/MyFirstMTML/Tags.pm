package MyFirstMTML::Tags;

use strict;

sub _hdlr_sample_block {
    my ( $ctx, $args, $cond ) = @_;
    my $from = $args->{ from } || 1;
    my $to = $args->{ to } || 10;
    my $glue = $args->{ glue } || ',';
    my $tokens  = $ctx->stash( 'tokens' );
    my $builder = $ctx->stash( 'builder' );
    my $res = '';
    for ( $from .. $to ) {
        local $ctx->{ __stash }{ vars }{ __counter__ } = $_;
        $ctx->stash( 'foo', $_ );
        my $out = $builder->build( $ctx, $tokens, $cond );
        if (! defined( $out ) ) { return $ctx->error( $builder->errstr ) };
        $res .= $out;
        $res .= $glue if ( $glue && ( $_ != $to ) );
    }
    $res;
}

sub _hdlr_if_sample_block_cond {
    my ( $ctx, $args, $cond ) = @_;
    return int( rand 2 );
}

sub _hdlr_sample_function_1 {
    my ( $ctx, $args, $cond ) = @_;
    return 'Hello MTML.';
}

sub _hdlr_sample_function_2 {
    my ( $ctx, $args, $cond ) = @_;
    my $prefix = $args->{ prefix }; # <MTSampleFunction2 prefix="(" suffix=")">
    my $suffix = $args->{ suffix }; # 
    my $foo = $ctx->stash( 'foo' );
    if ( $prefix ) {
        $foo = $prefix . $foo;
    }
    if ( $suffix ) {
        $foo .= $suffix;
    }
    return $foo;
}

sub _filter_sample_modifier {
    my ( $text, $arg ) = @_;  # <MTWebSiteName SampleModifier="!!">
    if (! $arg ) {
        $arg = '!!';
    }
    $text .= $arg;
    return $text;
}

1;