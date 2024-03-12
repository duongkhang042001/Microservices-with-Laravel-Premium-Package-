import { createRouter, createWebHistory, RouteLocation, RouteRecordRaw } from 'vue-router'
import Leaderboard from '@/components/Leaderboard/Leaderboard.vue';
import MainPage from '@/components/MainPage/MainPage.vue';
import Signin from '@/components/Auth/Signin.vue';
import Signup from '@/components/Auth/Signup.vue';
import EditProfile from '@/components/User/EditProfile.vue';
import Company from '@/components/Company/Company.vue';
import Profile from '@/components/Company/Profile.vue';
import Metrics from '@/components/Company/Metrics.vue'
import ScoreBoard from '@/components/Company/ScoreBoard.vue';
import IncomeStatement from '@/components/Company/FinancialStatements/IncomeStatement.vue';
import BalanceSheet from '@/components/Company/FinancialStatements/BalanceSheet.vue';
import CashFlow from '@/components/Company/FinancialStatements/CashFlow.vue';
import ComparisionBoard from '@/components/Company/ComparisionBoard.vue';
import Charts from '@/components/Company/Charts.vue';
import store from '@/store/Store';
import { BenchmarkType } from '@/models/Score';

const authGuard = (to: RouteLocation, from: RouteLocation, next: Function) => {
    if (!store.getters.isSignedIn) {
        next({ name: 'signIn' });
    } else {
        next();
    }
};

const routes: Array<RouteRecordRaw> = [
    // Guest routes
    { path: '/', name: 'main-page', component: MainPage },
    { path: '/sign-in', name: 'signIn', component: Signin },
    { path: '/sign-up', name: 'signUp', component: Signup },

    // Protected routes
    { path: '/leaderboard', name: 'leaderboard', component: Leaderboard, beforeEnter: authGuard },
    { path: '/profile/edit', name: 'edit-profile', component: EditProfile, beforeEnter: authGuard },
    {
        path: '/:ticker', name: 'company', component: Company, props: true, beforeEnter: authGuard,
        children: [
            { path: '', name: 'company-profile', component: Profile, props: true },
            { path: 'charts', name: 'company-charts', component: Charts, props: true },

            { path: 'metrics', name: 'company-metrics', component: Metrics, props: true },
            { path: 'income-statement', name: 'company-income-statement', component: IncomeStatement, props: true },
            { path: 'balance-sheet', name: 'company-balance-sheet', component: BalanceSheet, props: true },
            { path: 'cash-flow', name: 'company-cash-flow', component: CashFlow, props: true },

            { path: 'scores/all', name: 'company-scores', component: ScoreBoard, props: { benchmarkType: BenchmarkType.ALL } },
            { path: 'scores/sector', name: 'company-sector-scores', component: ScoreBoard, props: { benchmarkType: BenchmarkType.SECTOR } },
            { path: 'comparision', name: 'company-comparision', component: ComparisionBoard, props: true },
        ]
    },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior: (to, from) => {
    return new Promise((res, rej) => {
        // Ugyanazon az oldalon vagyunk, azonnal lehet scrollozni
        if (to.hash && to.path === from.path) {
            const element = document.getElementById(to.hash.slice(1));
            res({
                top: element?.offsetTop,
                behavior: 'smooth'
            });
        } else {    // Másik oldalról (komponensről) jövünk, kell egy kis idő, míg elérhető a DOM
            setTimeout(() => {
                if (to.hash) {
                    const element = document.getElementById(to.hash.slice(1));
                    res({
                        top: element?.offsetTop,
                        behavior: 'smooth'
                    });
                }
            }, 500);
        }
    });
  }
});

export default router
