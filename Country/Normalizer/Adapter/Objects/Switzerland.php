<?php
class   G_Country_Normalizer_Adapter_Objects_Switzerland 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/Switzerland|Suisse|Suiza|Svizzera|Schweiz|Su\\p{L}\\p{M}*\\p{L}\\p{M}*a/i';
	protected $_langISO = array(G_International_LangISO::FR,
								G_International_LangISO::DE,
								G_International_LangISO::IT);
}