<?php
class   G_Country_Normalizer_Adapter_Objects_Canada extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/[CK]anad\\p{L}\\p{M}*/i';
	protected $_langISO = array(G_International_LangISO::EN,
								G_International_LangISO::FR);
}