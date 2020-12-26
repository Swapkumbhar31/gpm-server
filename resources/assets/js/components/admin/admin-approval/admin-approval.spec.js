import Vue from 'vue';
import AdminApprovalComponent from './index.vue';

// Here are some Jasmine 2.0 tests, though you can
// use any test runner / assertion library combo you prefer
describe('AdminApprovalComponent', () => {
  // Inspect the raw component options
  it('has a created hook', () => {
    // expect(typeof AdminApprovalComponent.created).toBe('function');
  })
  // Evaluate the results of functions in
  // the raw component options
  it('sets the correct default data', () => {
    // expect(typeof AdminApprovalComponent.data).toBe('function')
    // const defaultData = AdminApprovalComponent.data();
    // expect(defaultData.message).toBe('hello!');
  })
  // Inspect the component instance on mount
  it('correctly sets the message when created', () => {
    // const vm = new Vue(AdminApprovalComponent).$mount();
    // expect(vm.message).toBe('bye!');
  })
  // Mount an instance and inspect the render output
  it('renders the correct message', () => {
    // const Ctor = Vue.extend(AdminApprovalComponent);
    // const vm = new Ctor().$mount();
    // expect(vm.$el.textContent).toBe('bye!');
  })
})