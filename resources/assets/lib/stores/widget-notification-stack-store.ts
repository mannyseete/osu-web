// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { dispatchListener } from 'app-dispatcher';
import DispatchListener from 'dispatch-listener';
import { NotificationBundleJson } from 'interfaces/notification-json';
import { action, computed } from 'mobx';
import {
  NotificationEventMoreLoaded,
  NotificationEventNew,
  NotificationEventRead,
} from 'notifications/notification-events';
import NotificationStackStore from './notification-stack-store';

@dispatchListener
export default class WidgetNotificationStackStore extends NotificationStackStore implements DispatchListener {
  @computed get totalWithPm() {
    return this.total + this.legacyPm.count;
  }

  @action
  handleNotificationEventMoreLoaded(event: NotificationEventMoreLoaded) {
    if (event.context.isWidget) {
      this.updateWithBundle(event.data);
    }
  }

  @action
  handleNotificationEventNew(event: NotificationEventNew) {
    if (event.data.is_read) return;

    super.handleNotificationEventNew(event);
  }

  @action
  handleNotificationEventRead(event: NotificationEventRead) {
    this.removeByEvent(event);
  }

  @action
  updateWithBundle(bundle: NotificationBundleJson) {
    super.updateWithBundle(bundle);

    if (bundle.unread_count != null) {
      this.total = bundle.unread_count;
    }
  }
}
