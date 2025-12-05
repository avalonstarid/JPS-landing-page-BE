<template>
  <!-- begin: container -->
  <div
    class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5"
  >
    <div class="flex flex-col justify-center gap-2">
      <h1 class="text-xl font-semibold leading-none text-gray-900">
        Dashboard
      </h1>
      <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
        Central Hub for Personal Customization
      </div>
    </div>
    <div class="flex items-center gap-2.5">
      <a
        class="btn btn-sm btn-light"
        href="html/demo1/public-profile/profiles/default.html"
      >
        View Profile
      </a>
    </div>
  </div>
  <!-- end: container -->
  <!-- begin: container -->
  <div class="grid gap-5 lg:gap-7.5">
    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-y-5 lg:gap-7.5 items-stretch">
      <div class="lg:col-span-1">
        <div class="grid grid-cols-2 gap-5 lg:gap-7.5 h-full items-stretch">
          <div
            class="card flex-col justify-between gap-6 h-full bg-cover bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg"
          >
            <img
              class="mt-4 w-7 ms-5"
              alt=""
              src="/media/brand-logos/linkedin-2.svg"
            />
            <div class="flex flex-col gap-1 px-5 pb-4">
              <span class="text-3xl font-semibold text-gray-900"> 9.3k </span>
              <span class="font-medium text-gray-600 text-2sm">
                Amazing mates
              </span>
            </div>
          </div>
          <div
            class="card flex-col justify-between gap-6 h-full bg-cover bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg"
          >
            <img
              class="mt-4 w-7 ms-5"
              alt=""
              src="/media/brand-logos/youtube-2.svg"
            />
            <div class="flex flex-col gap-1 px-5 pb-4">
              <span class="text-3xl font-semibold text-gray-900"> 24k </span>
              <span class="font-medium text-gray-600 text-2sm">
                Lessons Views
              </span>
            </div>
          </div>
          <div
            class="card flex-col justify-between gap-6 h-full bg-cover bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg"
          >
            <img
              class="mt-4 w-7 ms-5"
              alt=""
              src="/media/brand-logos/instagram-03.svg"
            />
            <div class="flex flex-col gap-1 px-5 pb-4">
              <span class="text-3xl font-semibold text-gray-900"> 608 </span>
              <span class="font-medium text-gray-600 text-2sm">
                New subscribers
              </span>
            </div>
          </div>
          <div
            class="card flex-col justify-between gap-6 h-full bg-cover bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg"
          >
            <img
              class="mt-4 dark:hidden w-7 ms-5"
              alt=""
              src="/media/brand-logos/tiktok.svg"
            />
            <img
              class="mt-4 light:hidden w-7 ms-5"
              alt=""
              src="/media/brand-logos/tiktok-dark.svg"
            />
            <div class="flex flex-col gap-1 px-5 pb-4">
              <span class="text-3xl font-semibold text-gray-900"> 2.5k </span>
              <span class="font-medium text-gray-600 text-2sm">
                Stream audience
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:col-span-2">
        <div class="h-full card">
          <div
            class="card-body p-10 bg-[length:80%] [background-position:175%_25%] bg-no-repeat entry-callout-bg"
          >
            <div class="flex flex-col justify-center gap-4">
              <div class="flex -space-x-2">
                <div class="flex">
                  <img
                    class="relative rounded-full hover:z-5 shrink-0 ring-1 ring-light-light size-10"
                    src="/media/avatars/300-4.png"
                  />
                </div>
                <div class="flex">
                  <img
                    class="relative rounded-full hover:z-5 shrink-0 ring-1 ring-light-light size-10"
                    src="/media/avatars/300-1.png"
                  />
                </div>
                <div class="flex">
                  <img
                    class="relative rounded-full hover:z-5 shrink-0 ring-1 ring-light-light size-10"
                    src="/media/avatars/300-2.png"
                  />
                </div>
                <div class="flex">
                  <span
                    class="relative inline-flex items-center justify-center text-xs font-semibold leading-none rounded-full hover:z-5 shrink-0 ring-1 size-10 text-success-inverse ring-success-light bg-success"
                  >
                    S
                  </span>
                </div>
              </div>
              <h2 class="text-1.5xl font-semibold text-gray-900">
                Connect Today &amp; Join
                <br />
                the
                <a class="link" href="#"> KeenThemes Network </a>
              </h2>
              <p class="text-sm font-medium text-gray-700 leading-5.5">
                Enhance your projects with premium themes and
                <br />
                templates. Join the KeenThemes community today
                <br />
                for top-quality designs and resources.
              </p>
            </div>
          </div>
          <div class="justify-center card-footer">
            <a
              class="btn btn-link"
              href="html/demo1/account/home/get-started.html"
            >
              Get Started
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- end: grid -->
    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
      <div class="lg:col-span-1">
        <div class="h-full card">
          <div class="card-header">
            <h3 class="card-title">Highlights</h3>
            <div class="menu" data-menu="true">
              <div
                class="menu-item"
                data-menu-item-offset="0, 10px"
                data-menu-item-placement="bottom-end"
                data-menu-item-toggle="dropdown"
                data-menu-item-trigger="click|lg:click"
              >
                <button
                  class="menu-toggle btn btn-sm btn-icon btn-light btn-clear"
                >
                  <i class="ki-filled ki-dots-vertical"> </i>
                </button>
                <div
                  class="menu-dropdown menu-default w-full max-w-[200px]"
                  data-menu-dismiss="true"
                >
                  <div class="menu-item">
                    <a
                      class="menu-link"
                      href="html/demo1/account/activity.html"
                    >
                      <span class="menu-icon">
                        <i class="ki-filled ki-cloud-change"> </i>
                      </span>
                      <span class="menu-title"> Activity </span>
                    </a>
                  </div>
                  <div class="menu-item">
                    <a
                      class="menu-link"
                      href="#"
                      data-modal-toggle="#share_profile_modal"
                    >
                      <span class="menu-icon">
                        <i class="ki-filled ki-share"> </i>
                      </span>
                      <span class="menu-title"> Share </span>
                    </a>
                  </div>
                  <div
                    class="menu-item"
                    data-menu-item-offset="-15px, 0"
                    data-menu-item-placement="right-start"
                    data-menu-item-toggle="dropdown"
                    data-menu-item-trigger="click|lg:hover"
                  >
                    <div class="menu-link">
                      <span class="menu-icon">
                        <i class="ki-filled ki-notification-status"> </i>
                      </span>
                      <span class="menu-title"> Notifications </span>
                      <span class="menu-arrow">
                        <i class="ki-filled ki-right text-3xs"> </i>
                      </span>
                    </div>
                    <div
                      class="menu-dropdown menu-default w-full max-w-[175px]"
                    >
                      <div class="menu-item">
                        <a
                          class="menu-link"
                          href="html/demo1/account/home/settings-sidebar.html"
                        >
                          <span class="menu-icon">
                            <i class="ki-filled ki-sms"> </i>
                          </span>
                          <span class="menu-title"> Email </span>
                        </a>
                      </div>
                      <div class="menu-item">
                        <a
                          class="menu-link"
                          href="html/demo1/account/home/settings-sidebar.html"
                        >
                          <span class="menu-icon">
                            <i class="ki-filled ki-message-notify"> </i>
                          </span>
                          <span class="menu-title"> SMS </span>
                        </a>
                      </div>
                      <div class="menu-item">
                        <a
                          class="menu-link"
                          href="html/demo1/account/home/settings-sidebar.html"
                        >
                          <span class="menu-icon">
                            <i class="ki-filled ki-notification-status"> </i>
                          </span>
                          <span class="menu-title"> Push </span>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="menu-item">
                    <a
                      class="menu-link"
                      href="#"
                      data-modal-toggle="#report_user_modal"
                    >
                      <span class="menu-icon">
                        <i class="ki-filled ki-dislike"> </i>
                      </span>
                      <span class="menu-title"> Report </span>
                    </a>
                  </div>
                  <div class="menu-separator"></div>
                  <div class="menu-item">
                    <a
                      class="menu-link"
                      href="html/demo1/account/home/settings-enterprise.html"
                    >
                      <span class="menu-icon">
                        <i class="ki-filled ki-setting-3"> </i>
                      </span>
                      <span class="menu-title"> Settings </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body flex flex-col gap-4 p-5 lg:p-7.5 lg:pt-4">
            <div class="flex flex-col gap-0.5">
              <span class="text-sm font-medium text-gray-600">
                All time sales
              </span>
              <div class="flex items-center gap-2.5">
                <span class="text-3xl font-semibold text-gray-900">
                  $295.7k
                </span>
                <span class="badge badge-outline badge-success badge-sm">
                  +2.7%
                </span>
              </div>
            </div>
            <div class="flex items-center gap-1 mb-1.5">
              <div class="bg-success h-2 w-full max-w-[60%] rounded-sm"></div>
              <div class="bg-brand h-2 w-full max-w-[25%] rounded-sm"></div>
              <div class="bg-info h-2 w-full max-w-[15%] rounded-sm"></div>
            </div>
            <div class="flex flex-wrap items-center gap-4 mb-1">
              <div class="flex items-center gap-1.5">
                <span class="badge badge-dot size-2 badge-success"> </span>
                <span class="text-sm font-medium text-gray-700">
                  Metronic
                </span>
              </div>
              <div class="flex items-center gap-1.5">
                <span class="badge badge-dot size-2 badge-danger"> </span>
                <span class="text-sm font-medium text-gray-700"> Bundle </span>
              </div>
              <div class="flex items-center gap-1.5">
                <span class="badge badge-dot size-2 badge-info"> </span>
                <span class="text-sm font-medium text-gray-700">
                  MetronicNest
                </span>
              </div>
            </div>
            <div class="border-b border-gray-300"></div>
            <div class="grid gap-3">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5">
                  <i class="text-base text-gray-500 ki-filled ki-shop"> </i>
                  <span class="text-sm font-medium text-gray-800">
                    Online Store
                  </span>
                </div>
                <div
                  class="flex items-center gap-6 text-sm font-medium text-gray-800"
                >
                  <span class="lg:text-right"> $172k </span>
                  <span class="lg:text-right">
                    <i class="ki-filled ki-arrow-up text-success"> </i>
                    3.9%
                  </span>
                </div>
              </div>
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5">
                  <i class="text-base text-gray-500 ki-filled ki-facebook"> </i>
                  <span class="text-sm font-medium text-gray-800">
                    Facebook
                  </span>
                </div>
                <div
                  class="flex items-center gap-6 text-sm font-medium text-gray-800"
                >
                  <span class="lg:text-right"> $85k </span>
                  <span class="lg:text-right">
                    <i class="ki-filled ki-arrow-down text-danger"> </i>
                    0.7%
                  </span>
                </div>
              </div>
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5">
                  <i class="text-base text-gray-500 ki-filled ki-instagram">
                  </i>
                  <span class="text-sm font-medium text-gray-800">
                    Instagram
                  </span>
                </div>
                <div
                  class="flex items-center gap-6 text-sm font-medium text-gray-800"
                >
                  <span class="lg:text-right"> $36k </span>
                  <span class="lg:text-right">
                    <i class="ki-filled ki-arrow-up text-success"> </i>
                    8.2%
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:col-span-2">
        <div class="h-full card">
          <div class="card-header">
            <h3 class="card-title">Earnings</h3>
            <div class="flex gap-5">
              <label class="switch switch-sm">
                <input class="order-2" name="check" type="checkbox" value="1" />
                <span class="order-1 switch-label"> Referrals only </span>
              </label>
              <select class="select select-sm w-28" name="select">
                <option value="1">1 month</option>
                <option value="2">3 month</option>
                <option value="3">6 month</option>
                <option selected value="4">12 month</option>
              </select>
            </div>
          </div>
          <div
            class="flex flex-col items-stretch justify-end px-3 py-1 card-body grow"
          >
            <div id="earnings_chart"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- end: grid -->
    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
      <div class="lg:col-span-1">
        <div class="h-full card">
          <div class="card-body lg:p-7.5 lg:pt-6 p-5">
            <div
              class="flex items-center justify-between flex-wrap gap-5 mb-7.5"
            >
              <div class="flex flex-col gap-1">
                <span class="text-1.5xl font-semibold text-gray-900">
                  Team Meeting
                </span>
                <span class="text-sm font-semibold text-gray-800">
                  09:00 - 09:30
                </span>
              </div>
              <img class="size-7" alt="" src="/media/brand-logos/zoom.svg" />
            </div>
            <p class="text-sm font-normal text-gray-800 leading-5.5 mb-8">
              Team meeting to discuss strategies, outline
              <br />
              project milestones, define key goals, and
              <br />
              establish clear timelines.
            </p>
            <div class="flex gap-10 p-5 bg-gray-100 rounded-lg">
              <div class="flex flex-col gap-5">
                <div
                  class="flex items-center gap-1.5 text-sm font-medium text-gray-800"
                >
                  <i class="text-base text-gray-500 ki-filled ki-geolocation">
                  </i>
                  Location
                </div>
                <div class="text-sm font-semibold text-gray-800 pt-1.5">
                  Amsterdam
                </div>
              </div>
              <div class="flex flex-col gap-5">
                <div
                  class="flex items-center gap-1.5 text-sm font-medium text-gray-800"
                >
                  <i class="text-base text-gray-500 ki-filled ki-users"> </i>
                  Team
                </div>
                <div class="flex -space-x-2">
                  <div class="flex">
                    <img
                      class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                      src="/media/avatars/300-4.png"
                    />
                  </div>
                  <div class="flex">
                    <img
                      class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                      src="/media/avatars/300-1.png"
                    />
                  </div>
                  <div class="flex">
                    <img
                      class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                      src="/media/avatars/300-2.png"
                    />
                  </div>
                  <div class="flex">
                    <span
                      class="hover:z-5 relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none size-[30px] text-success-inverse text-4xs ring-success-light bg-success"
                    >
                      +10
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="justify-center card-footer">
            <a class="btn btn-link" href=""> Join Meeting </a>
          </div>
        </div>
      </div>
      <div class="lg:col-span-2">
        <div class="grid">
          <div class="h-full min-w-full card card-grid">
            <div class="card-header">
              <h3 class="card-title">Teams</h3>
              <div class="input input-sm max-w-48">
                <i class="ki-filled ki-magnifier"> </i>
                <input placeholder="Search Teams" type="text" />
              </div>
            </div>
            <div class="card-body">
              <div data-datatable="true" data-datatable-page-size="5">
                <div class="scrollable-x-auto">
                  <table class="table table-border" data-datatable-table="true">
                    <thead>
                      <tr>
                        <th class="w-[60px]">
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            data-datatable-check="true"
                          />
                        </th>
                        <th class="w-[280px]">
                          <span class="sort asc">
                            <span class="sort-label"> Team </span>
                            <span class="sort-icon"> </span>
                          </span>
                        </th>
                        <th class="min-w-[135px]">
                          <span class="sort">
                            <span class="sort-label"> Rating </span>
                            <span class="sort-icon"> </span>
                          </span>
                        </th>
                        <th class="min-w-[135px]">
                          <span class="sort">
                            <span class="sort-label"> Last Modified </span>
                            <span class="sort-icon"> </span>
                          </span>
                        </th>
                        <th class="min-w-[135px]">
                          <span class="sort">
                            <span class="sort-label"> Members </span>
                            <span class="sort-icon"> </span>
                          </span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="1"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Product Management
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Product development &amp; lifecycle
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>21 Oct, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-4.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-1.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-2.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success"
                              >
                                +10
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="2"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Marketing Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Campaigns &amp; market analysis
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label indeterminate">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                                style="width: 50%"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>15 Oct, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-4.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="hover:z-5 relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] uppercase text-warning-inverse ring-warning-light bg-warning"
                              >
                                g
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="3"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              HR Department
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Talent acquisition, employee welfare
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>10 Oct, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-4.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-1.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-2.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info"
                              >
                                +A
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="4"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Sales Division
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Customer relations, sales strategy
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>05 Oct, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-24.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-7.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="5"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Development Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Software development
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label indeterminate">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                                style="width: 50%"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>01 Oct, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-3.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-8.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-9.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-danger-inverse ring-danger-light bg-danger"
                              >
                                +5
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="6"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Quality Assurance
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Product testing
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>25 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-6.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-5.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="7"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Finance Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Financial planning
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>20 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-10.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-11.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-12.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary"
                              >
                                +8
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="8"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Customer Support
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Customer service
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label indeterminate">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                                style="width: 50%"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>15 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-13.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-14.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="9"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              R&amp;D Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Research &amp; development
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>10 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-15.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-16.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="10"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Operations Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Operations management
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>05 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-17.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-18.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-19.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="11"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              IT Support
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Technical support
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>01 Sep, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-20.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-21.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="12"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Legal Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Legal support
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>25 Aug, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-22.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-23.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="13"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Logistics Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Supply chain
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label indeterminate">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                                style="width: 50%"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>20 Aug, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-24.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-25.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="14"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Procurement Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Supplier management
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>15 Aug, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-26.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-27.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-28.png"
                              />
                            </div>
                            <div class="flex">
                              <span
                                class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info"
                              >
                                +3
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input
                            class="checkbox checkbox-sm"
                            type="checkbox"
                            value="15"
                            data-datatable-row-check="true"
                          />
                        </td>
                        <td>
                          <div class="flex flex-col gap-2">
                            <a
                              class="text-sm font-semibold leading-none text-gray-900 hover:text-primary"
                              href="#"
                            >
                              Training Team
                            </a>
                            <span class="leading-3 text-gray-600 text-2sm">
                              Employee training
                            </span>
                          </div>
                        </td>
                        <td>
                          <div class="rating">
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label checked">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                            <div class="rating-label">
                              <i
                                class="text-base leading-none rating-on ki-solid ki-star"
                              >
                              </i>
                              <i
                                class="text-base leading-none rating-off ki-outline ki-star"
                              >
                              </i>
                            </div>
                          </div>
                        </td>
                        <td>10 Aug, 2024</td>
                        <td>
                          <div class="flex -space-x-2">
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-29.png"
                              />
                            </div>
                            <div class="flex">
                              <img
                                class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]"
                                src="/media/avatars/300-30.png"
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div
                  class="flex-col justify-center gap-5 font-medium text-gray-600 card-footer md:justify-between md:flex-row text-2sm"
                >
                  <div class="flex items-center order-2 gap-2 md:order-1">
                    Show
                    <select
                      class="w-16 select select-sm"
                      name="perpage"
                      data-datatable-size="true"
                    ></select>
                    per page
                  </div>
                  <div class="flex items-center order-1 gap-4 md:order-2">
                    <span data-datatable-info="true"> </span>
                    <div
                      class="pagination"
                      data-datatable-pagination="true"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end: grid -->
  </div>
  <!-- end: container -->
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Dashboard',
})
</script>

<style scoped>
.channel-stats-bg {
  background-image: url('/media/images/2600x1600/bg-3.png');
}
.dark .channel-stats-bg {
  background-image: url('/media/images/2600x1600/bg-3-dark.png');
}

.entry-callout-bg {
  background-image: url('/media/images/2600x1600/2.png');
}
.dark .entry-callout-bg {
  background-image: url('/media/images/2600x1600/2-dark.png');
}
</style>
