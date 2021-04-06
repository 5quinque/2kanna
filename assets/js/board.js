import '../css/prism.css';

import * as Collapse from './collapse';
import * as Image from './image';
import * as Reply from './reply';
import './upload';
import './prism';

Reply.setStickyOnclick();
Reply.setDeleteOnclick();

Collapse.setCollapseOnclick();
Image.setImageOnclick();
