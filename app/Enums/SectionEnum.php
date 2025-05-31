<?php

namespace App\Enums;

enum SectionEnum: string
{
    const BG = 'bg_image';

    case HOME_EXAMPLE = 'home example section';
    case HOME_EXAMPLES = 'home example sections';

    case HOME_INTRO = 'home intro section';
    case HOME_INTROS = 'home intro sections';

    //common sections
    case FOOTER = 'footer section';
    case HEADER = 'header section';
    
}
