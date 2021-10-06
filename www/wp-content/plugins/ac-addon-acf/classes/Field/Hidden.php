<?php

namespace ACA\ACF\Field;

use AC;
use ACP;
use ACA\ACF\Field;
use ACA\ACF\Filtering;
use ACA\ACF\Formattable;
use ACA\ACF\Search;
use ACP\Search\Comparison\Meta;
use ACA\ACF\Sorting;
use ACP\Sorting\Model\MetaFormatFactory;
use ACP\Sorting\Model\MetaFactory;
use ACP\Sorting\Type\DataType;


class Hidden extends Field
    implements Formattable {

    public function __construct( $column ) {
        parent::__construct( $column );

        $this->column->set_serialized( true );
    }

    public function filtering() {
        return new Filtering\Hidden( $this->column );
    }

    public function search() {
        return new ACP\Search\Comparison\Meta\Number( $this->get_meta_key(), $this->get_meta_type() );
    }

    public function sorting() {
        return ( new ACP\Sorting\Model\MetaFactory() )->create( $this->get_meta_type(), $this->get_meta_key(), new DataType( DataType::NUMERIC ) );
    }


    public function format( $value ) {
        if ( empty( $values ) ) {
            return false;
        }

        return implode( ', ', (array) $values );
    }
}
