<?php
class 		Tesseract
{
	protected $sides
		, $labels		= array()
		, $content		= array()
	;

	public function __construct($sides)
	{
		$this->sides = $sides;
	}

	public function label()
	{
		foreach(func_get_args() as $arg)
		{
			if(count($this->labels) > $this->sides)
			{
				return false;
			}

			$this->labels[] = $arg;
		}
	}

	public function rotate($a, $b)
	{
		if(!is_numeric($a))
		{
			$a = $this->resolve($a);
		}

		if(!is_numeric($b))
		{
			$b = $this->resolve($b);
		}

		if($a === false || $b === false)
		{
			return false;
		}

		if($a > $this->sides || $b > $this->sides)
		{
			return false;
		}

		if($a > $b)
		{
			list($a, $b) = array($b, $a);
		}

		$obj = $this;

		while($a < $b)
		{
			$obj = $obj->swap(--$b);
		}

		return $obj;
	}

	public function wrap($content)
	{
		$this->content	= $content;
	}

	public function unwrap()
	{
		return $this->content;
	}

	public function lift()
	{
		$input = func_get_args();

		$t			= new static($this->sides);
		$t->wrap($this->unwrap());
		call_user_func_array(array($t, 'label'), $this->labels);

		while($label = array_shift($input))
		{
			array_unshift($t->labels, $label);
			$t->content = array($t->content);
			$t->sides++;
		}

		return $t;
	}

	public function flatten($dimension = 0, $depth = 0)
	{
		$iDimension		= $dimension;

		if(!is_numeric($dimension))
		{
			$iDimension	= $this->resolve($dimension);
		}

		if($iDimension)
		{
			$t			= $this->rotate(0, $iDimension);
		}
		else
		{
			$t			= new static($this->sides);
			$t->wrap($this->unwrap());
			call_user_func_array(array($t, 'label'), $this->labels);
		}

		$levelsDeep		= $this->sides - 1 - $depth;

		while(--$levelsDeep)
		{
			foreach($t->content as &$topLevel)
			{
				$newLevel		= array();

				foreach($topLevel as &$nextLevel)
				{
					$newLevel	= array_merge($newLevel, $nextLevel);
				}

				$topLevel		= $newLevel;
			}

			$t->sides--;
			$t->labels[1] .= ',' . implode('', array_splice($t->labels, 1, 1));
		}

		return $t;
	}

	public function &swap($depth, &$superRow = NULL)
	{
		$level		=& $this->content;
		$newlevel	= array();

		if($superRow)
		{
			$level =& $superRow;
		}

		foreach($level as $k => &$row)
		{
			if(!$depth)
			{
				foreach($row as $kk => &$column)
				{
					$newlevel[$kk][$k] =& $column;
				}
			}
			else
			{
				$newlevel[$k] = $this->swap($depth-1, $row);
			}
		}

		if($superRow !== NULL)
		{
			return $newlevel;
		}
		else
		{
			$t = new static($this->sides);

			$t->wrap($newlevel);

			foreach($this->labels as $i => $label)
			{
				if($i == $depth)
				{
					$t->label($this->labels[$depth+1]);
				}
				elseif($i == $depth+1)
				{
					$t->label($this->labels[$depth]);
				}
				else
				{
					$t->label($this->labels[$i]);
				}
			}

			return $t;
		}
	}

	public function get()
	{
		$input = func_get_args();
		$level =& $this->content;

		if(count($input) > ($this->sides))
		{
			return false;
		}

		while(count($input))
		{
			$degree = array_shift($input);

			if(!isset($level[$degree]))
			{
				$level[$degree] = array();
			}

			$level =& $level[$degree];

			if(count($input) == 0)
			{
				return $level;
			}
		}
	}

	public function insert()
	{
		$input = func_get_args();
		$level =& $this->content;

		if(count($input) !== ($this->sides+1))
		{
			return false;
		}

		while(count($input))
		{
			$degree = array_shift($input);

			if(!isset($level[$degree]))
			{
				$level[$degree] = array();
			}

			ksort($level);

			$level =& $level[$degree];

			if(count($input) == 1)
			{
				$level = array_shift($input);
			}
		}

		return true;
	}

	public function delete()
	{
		$input = func_get_args();
		$level =& $this->content;

		if(count($input) !== ($this->sides))
		{
			return false;
		}

		while(count($input))
		{
			$degree = array_shift($input);

			if(count($input) == 0)
			{
				unset($level[$degree]);
			}
			else
			{
				$level =& $level[$degree];
			}
		}

		return true;
	}

	protected function resolve($label)
	{
		return array_search($label, $this->labels);
	}
}
