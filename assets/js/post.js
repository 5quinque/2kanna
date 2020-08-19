import '../css/prism.css';

import * as Autoupdate from './autoupdate';
import * as Collapse from './collapse';
import * as Reply from './reply';
import * as Image from './image';
import './upload';
import './prism';

Reply.highlightReply();
Reply.setReplyOnclick();
Reply.setDeleteOnclick();
Reply.setStickyOnclick();
Collapse.setCollapseOnclick();
Image.setImageOnclick();

setInterval(Autoupdate.updatePosts, 10000);

