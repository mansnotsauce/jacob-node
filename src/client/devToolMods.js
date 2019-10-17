// https://stackoverflow.com/questions/55733647/chrome-devtools-formatter-for-javascript-proxy
import { toJS } from './framework'

// track all proxies in weakset (allows GC)
const proxy_set = new WeakSet();
window.Proxy = new Proxy(Proxy, {
      construct(target, args) {
        const proxy = new target(args[0], args[1]);
        proxy_set.add(proxy);
        return proxy;
      },
});

window.devtoolsFormatters = [{
  header(obj) {
    try {
      if (!proxy_set.has(obj)) {
        return null;
      }
      return ['object', {object: toJS(obj)}]
    } catch (e) {
      return null;
    }
},
  hasBody() {
      return false;
  },
}];
