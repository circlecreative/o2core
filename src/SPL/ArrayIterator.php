<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 6:58 PM
 */

namespace O2System\Core\SPL;


class ArrayIterator extends \ArrayObject
{
	private $position = 0;

	public function setPosition( $position )
	{
		$this->position = (int) $position;

		return $this;
	}

	public function setCurrent( $position )
	{
		return $this->setPosition( $position );
	}

	public function seek( $position )
	{
		$position = $position < 0 ? 0 : $position;

		if ( $this->offsetExists( $position ) )
		{
			$this->position = $position;

			return $this->offsetGet( $position );
		}

		return NULL;
	}

	public function rewind()
	{
		$this->position = 0;

		return $this->seek( $this->position );
	}

	public function current()
	{
		return $this->seek( $this->position );
	}

	public function key()
	{
		return $this->position;
	}

	public function next()
	{
		++$this->position;

		return $this->seek( $this->position );
	}

	public function previous()
	{
		--$this->position;

		return $this->seek( $this->position );
	}

	public function first()
	{
		return $this->seek( 0 );
	}

	public function last()
	{
		return $this->seek( $this->count() - 1 );
	}

	public function valid()
	{
		return $this->offsetExists( $this->position );
	}

	/**
	 * __isEmpty
	 *
	 * Validate ArrayObject is empty
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return (bool) ( $this->count() == 0 ) ? TRUE : FALSE;
	}

	// ------------------------------------------------------------------------

	public function __isset( $index )
	{
		return $this->offsetExists( $index );
	}

	/**
	 * Array Limit
	 *
	 * Limit amount of array
	 *
	 * @param   array      $array Array Content
	 * @param   int|string $limit Num of limit
	 *
	 * @return  array
	 */
	public function getArrayCopy( $limit = 0 )
	{
		if ( $limit > 0 AND $this->count() > 0 )
		{
			$i = 0;
			foreach ( parent::getArrayCopy() as $key => $value )
			{
				if ( $i < $limit )
				{
					$ArrayCopy[ $key ] = $value;
				}
				$i++;
			}

			return $ArrayCopy;
		}

		return parent::getArrayCopy();
	}

	public function getArrayKeys( $seach_value = NULL, $strict = FALSE )
	{
		return array_keys( $this->getArrayCopy(), $seach_value, $strict );
	}

	public function getArraySlice( $offset = 0, $limit, $preserve_keys = FALSE )
	{
		return array_slice( $this->getArrayCopy(), $offset, $limit, $preserve_keys );
	}

	public function getArraySlices( array $slices, $preserve_keys = FALSE )
	{
		$ArrayCopy = $this->getArrayCopy();

		foreach ( $slices as $key => $limit )
		{
			$ArraySlices[ $key ] = array_slice( $ArrayCopy, 0, $limit, $preserve_keys );
		}

		return $ArraySlices;
	}

	public function getArrayChunk( $size, $preserve_keys = FALSE )
	{
		return array_chunk( $this->getArrayCopy(), $size, $preserve_keys );
	}

	public function getArrayChunks( array $chunks, $preserve_keys = FALSE )
	{
		$ArrayCopy = $this->getArrayCopy();

		$offset = 0;
		foreach ( $chunks as $key => $limit )
		{
			$ArrayChunks[ $key ] = array_slice( $ArrayCopy, $offset, $limit, $preserve_keys );
			$offset              = $limit;
		}

		return $ArrayChunks;
	}

	public function getArrayShuffle( $limit = 0 )
	{
		$ArrayCopy = $this->getArrayCopy( $limit );
		shuffle( $ArrayCopy );

		return $ArrayCopy;
	}

	public function getArrayReverse()
	{
		$ArrayCopy = $this->getArrayCopy();

		return array_reverse( $ArrayCopy );
	}

	public function __toObject( $depth = 0 )
	{
		return $this->___toObjectIterator( $this->getArrayCopy(), ( $depth == 0 ? 'ALL' : $depth ) );
	}

	private function ___toObjectIterator( $array, $depth = 'ALL', $counter = 0 )
	{
		$ArrayObject = new ArrayObject();

		if ( $this->count() > 0 )
		{
			foreach ( $array as $key => $value )
			{
				if ( strlen( $key ) )
				{
					if ( is_array( $value ) )
					{
						if ( $depth == 'ALL' )
						{
							$ArrayObject->offsetSet( $key, $this->___toObjectIterator( $value, $depth ) );
						}
						elseif ( is_numeric( $depth ) )
						{
							if ( $counter != $depth )
							{
								$ArrayObject->offsetSet( $key, $this->___toObjectIterator( $value, $depth, $counter ) );
							}
							else
							{
								$ArrayObject->offsetSet( $key, $value );
							}
						}
						elseif ( is_string( $depth ) && $key == $depth )
						{
							$ArrayObject->offsetSet( $key, $value );
						}
						elseif ( is_array( $depth ) && in_array( $key, $depth ) )
						{
							$ArrayObject->offsetSet( $key, $value );
						}
						else
						{
							$ArrayObject->offsetSet( $key, $this->___toObjectIterator( $value, $depth ) );
						}
					}
					else
					{
						$ArrayObject->offsetSet( $key, $value );
					}
				}
			}
		}

		return $ArrayObject;
	}
}